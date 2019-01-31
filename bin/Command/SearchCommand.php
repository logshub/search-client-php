<?php
namespace Logshub\Search\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Logshub\SearchClient\Client;

class SearchCommand extends Command
{
    protected function configure()
    {
        $this->setName("search")
            ->setDescription("Search documents from logshub.com search service")
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Path to INI file with logshub configuration', null)
            ->addOption('query', null, InputOption::VALUE_REQUIRED, 'Search query', null)
            ->addOption('secure', null, InputOption::VALUE_OPTIONAL, 'Whether connection should be secure. No changes recommended', true)
            ->addOption('domain', null, InputOption::VALUE_OPTIONAL, 'Logshub search API domain without location. No changes recommended', 'apisearch.logshub.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isValid($input, $output)) {
            return;
        }

        $config = $input->getOption('config');
        $query = $input->getOption('query');

        $configuration = $this->parseConfig($config);
        if (!$configuration) {
            return self::printError($output, 'Configuration file is not valid');
        }
        $client = $this->getClient($input, $configuration);
        $request = new \Logshub\SearchClient\Request\SearchProducts(
            $configuration['pub_key'],
            $query,
            'products,categories,aggcategories'
        );
        $response = $client->searchProducts($request);

        $output->writeln($response->getStatusCode());

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name', 'Price']);

        foreach ($response->getCategories() as $cat) {
            $table->addRow([
                $cat->getId(),
                'Category: '. $cat->getName(),
                '',
            ]);
        }

        foreach ($response->getProducts() as $prod) {
            $table->addRow([
                $prod->getId(),
                $prod->getName(),
                $prod->getPrice(),
            ]);
        }

        $table->render();
    }

    /**
     * @return array|false
     */
    protected function parseConfig($configFilePath)
    {
        $config = \parse_ini_file($configFilePath);
        if (empty($config['pub_key'])) {
            return false;
        }

        return $config;
    }

    protected function isValid(InputInterface $input, OutputInterface $output)
    {
        $config = $input->getOption('config');
        $query = $input->getOption('query');

        if (!\is_file($config)) {
            return self::printError($output, 'Config file does not exists');
        }
        if (!\is_readable($config)) {
            return self::printError($output, 'Config file is not readable');
        }
        if (!$query) {
            return self::printError($output, 'Query is not vailid');
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
