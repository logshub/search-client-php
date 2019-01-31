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
            ->addOption('secure', null, InputOption::VALUE_OPTIONAL, 'Whether connection should be secure. No changes recommended', true)
            ->addOption('domain', null, InputOption::VALUE_OPTIONAL, 'Logshub search API domain without location. No changes recommended', 'apisearch.logshub.com')
            ->addOption('categories', null, InputOption::VALUE_NONE, 'Flag whether to import as categories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isValid($input, $output)) {
            return;
        }

        $config = $input->getOption('config');
        $csv = $input->getOption('csv');
        $isCategoriesImport = (bool)$input->getOption('categories');

        $configuration = $this->parseConfig($config);
        if (!$configuration) {
            return self::printError($output, 'Configuration file is not valid');
        }
        $client = $this->getClient($input, $configuration);

        if (($handle = fopen($csv, "r")) === false) {
            return self::printError($output, 'Unable to read CSV file');
        }
        $counter = 0;
        while (($csvRow = fgetcsv($handle, 1000, ",")) !== false) {
            $counter++;
            // header
            if ($counter === 1) {
                continue;
            }
            $row = new Row($csvRow);
            $this->enqueue($row, $isCategoriesImport, $output);
            // real sending will be every 10th document
            if ($counter % 10 === 0) {
                $this->sendEnqueued($client, $configuration['serviceid'], $isCategoriesImport, $counter, $output);
            }
        }
        fclose($handle);
        // make sure that all the products are sent
        $this->sendEnqueued($client, $configuration['serviceid'], $isCategoriesImport, $counter, $output);
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

    /**
     * @return array|false
     */
    protected function parseConfig($configFilePath)
    {
        $config = \parse_ini_file($configFilePath);
        if (empty($config['serviceid']) || empty($config['location']) || empty($config['apihash']) || empty($config['apisecret'])) {
            return false;
        }

        return $config;
    }

    protected function isValid(InputInterface $input, OutputInterface $output)
    {
        $config = $input->getOption('config');
        $csv = $input->getOption('csv');

        if (!\is_file($config)) {
            return self::printError($output, 'Config file does not exists');
        }
        if (!\is_readable($config)) {
            return self::printError($output, 'Config file is not readable');
        }
        if (!\is_file($csv)) {
            return self::printError($output, 'CSV file does not exists');
        }
        if (!\is_readable($csv)) {
            return self::printError($output, 'CSV file is not readable');
        }

        return true;
    }

    protected function getClient(InputInterface $input, array $configuration)
    {
        $httpClient = new \GuzzleHttp\Client([
            'verify' => (bool)$input->getOption('secure')
        ]);
        $url = 'https://' . $configuration['location'] . '.' . $input->getOption('domain');

        return new Client(
            $httpClient,
            $url,
            $configuration['apihash'],
            $configuration['apisecret']
        );
    }

    private static function printError(OutputInterface $output, $error)
    {
        $output->writeln('ERROR: ' . $error);

        return false;
    }
}
