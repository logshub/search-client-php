<?php
namespace Logshub\Search\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Logshub\SearchClient\Client;

class IndexCommand extends Command
{
    /**
     * @var \Logshub\SearchClient\Config\File
     */
    protected $config;

    protected function configure()
    {
        // TODO: other product fields
        $this->setName("index")
            ->setDescription("Index document into logshub.com search service")
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Path to INI file with logshub configuration', null)
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'ID of a new document from your system', null)
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name of a new document', null)
            ->addOption('price', null, InputOption::VALUE_OPTIONAL, 'Price of a new document', null)
            ->addOption('secure', null, InputOption::VALUE_OPTIONAL, 'Whether connection should be secure. No changes recommended', true)
            ->addOption('domain', null, InputOption::VALUE_OPTIONAL, 'Logshub search API domain without location. No changes recommended', 'apisearch.logshub.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isValid($input, $output)) {
            return;
        }

        $id = $input->getOption('id');
        $name = $input->getOption('name');
        $price = $input->getOption('price');

        $this->config = new \Logshub\SearchClient\Config\File($input->getOption('config'));
        $client = $this->getClient($input);

        $product = new \Logshub\SearchClient\Model\Product($id, [
            'name' => $name,
            'price' => $price
        ]);
        $request = new \Logshub\SearchClient\Request\IndexProducts($this->config->getServiceId(), [
            $product
        ]);
        $response = $client->indexProducts($request);

        $output->writeln($response->getStatusCode() . ' ' . $response->getBody());
    }

    protected function isValid(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getOption('id');
        $name = $input->getOption('name');
        $price = $input->getOption('price');

        if (!$id) {
            return self::printError($output, 'Id is not vailid');
        }
        if (!$name) {
            return self::printError($output, 'Name is not vailid');
        }
        if ($price && !\is_numeric($price)) {
            return self::printError($output, 'Price is not vailid');
        }

        return true;
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
