<?php
namespace Logshub\Search\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Logshub\SearchClient\Client;
use Logshub\SearchClient\Csv\Row;

class IndexCsvCommand extends Command
{
    /**
     * @var \Logshub\SearchClient\Config\File
     */
    protected $config;
    /**
     * Queue of documents to send
     * @var array
     */
    protected $documentsToSend = [];

    protected function configure()
    {
        $this->setName("index:csv")
            ->setDescription("Index documents from CSV file into logshub.com search service")
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Path to INI file with logshub configuration', null)
            ->addOption('csv', null, InputOption::VALUE_REQUIRED, 'Path to CSV file with products', null)
            ->addOption('csv-separator', null, InputOption::VALUE_OPTIONAL, 'CSV separator, comma by default', ',')
            ->addOption('batch-size', null, InputOption::VALUE_OPTIONAL, 'Batch size, 10 by default', 10)
            ->addOption('secure', null, InputOption::VALUE_OPTIONAL, 'Whether connection should be secure. No changes recommended', true)
            ->addOption('domain', null, InputOption::VALUE_OPTIONAL, 'Logshub search API domain without location. No changes recommended', 'apisearch.logshub.com')
            ->addOption('categories', null, InputOption::VALUE_NONE, 'Flag whether to import as categories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = (int)$input->getOption('batch-size');
        $isCategoriesImport = (bool)$input->getOption('categories');

        $this->config = new \Logshub\SearchClient\Config\File($input->getOption('config'));
        $csv = new \Logshub\SearchClient\Csv\File(
            $input->getOption('csv'),
            $input->getOption('csv-separator')
        );
        $client = $this->getClient($input);

        $counter = 0;
        $rows = $csv->getRows();
        foreach ($rows as $row){
            $counter++;
            $this->enqueue($row, $isCategoriesImport, $output);
            // real sending will be every Xth document
            if ($counter % $batchSize === 0) {
                $this->sendEnqueued($client, $this->config->getServiceId(), $isCategoriesImport, $counter, $output);
            }
        }
        // make sure that all the products are sent
        $this->sendEnqueued($client, $this->config->getServiceId(), $isCategoriesImport, $counter, $output);
    }

    protected function enqueue(Row $row, $isCategoriesImport, OutputInterface $output)
    {
        // TODO validation
        $this->documentsToSend[] = $isCategoriesImport ? $row->toCategory() : $row->toProduct();

        $output->writeln('Enqueued #' . $row->getId());
    }

    protected function sendEnqueued(Client $client, $serviceId, $isCategoriesImport, $counter, OutputInterface $output)
    {
        if (empty($this->documentsToSend)) {
            return;
        }
        try {
            if ($isCategoriesImport) {
                $request = new \Logshub\SearchClient\Request\IndexCategories($serviceId, $this->documentsToSend);
                $response = $client->indexCategories($request);
            } else {
                $request = new \Logshub\SearchClient\Request\IndexProducts($serviceId, $this->documentsToSend);
                $response = $client->indexProducts($request);
            }
            $output->writeln('COUNTER:'.$counter. '; ' . $response->getStatusCode() . ' ' . $response->getBody());
        } catch (\Exception $e) {
            $output->writeln('ERROR: '.$e->getMessage());
            if ($e->getPrevious()) {
                $output->writeln('ERROR: '.$e->getPrevious()->getMessage());
            }
            $output->writeln('ERROR CTX: '.print_r($this->documentsToSend, true));
        }

        $this->documentsToSend = [];
    }

    protected function getClient(InputInterface $input)
    {
        $httpClient = new \GuzzleHttp\Client([
            'verify' => (bool)$input->getOption('secure')
        ]);
        $url = 'https://' . $this->config->getLocation() . '.' . $input->getOption('domain');

        return new Client(
            $httpClient,
            $url,
            $this->config->getApiHash(),
            $this->config->getApiSecret()
        );
    }

    private static function printError(OutputInterface $output, $error)
    {
        $output->writeln('ERROR: ' . $error);

        return false;
    }
}
