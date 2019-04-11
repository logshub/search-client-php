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
    /**
     * @var \Logshub\SearchClient\Config\File
     */
    protected $config;
    
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

        $this->config = new \Logshub\SearchClient\Config\File($input->getOption('config'));
        $client = $this->getClient($input);
        $request = new \Logshub\SearchClient\Request\SearchProducts(
            $this->config->getPubKey(),
            $input->getOption('query'),
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

    protected function isValid(InputInterface $input, OutputInterface $output)
    {
        $query = $input->getOption('query');
        if (!$query) {
            return self::printError($output, 'Query is not vailid');
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
