<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class Priorities implements RequestInterface
{
    /**
     * @var string
     */
    protected $serviceId;
    /**
     * @var int
     */
    protected $page;

    public function __construct($serviceId, $page = 1)
    {
        $this->serviceId = $serviceId;
        $this->page = $page;
    }

    public function isValid()
    {
        if (!$this->serviceId) {
            throw new \InvalidArgumentException('Service ID param is not valid');
        }
        if (!\is_numeric($this->page)) {
            throw new \InvalidArgumentException('Page param is not valid');
        }

        return true;
    }

    public function send(\Logshub\SearchClient\Client $client)
    {
        // throws exception in case of validation failure
        $this->isValid();
        
        $res = $client->getHttpClient()->request('GET', $client->getUrl() . '/v1/service/' . $this->serviceId . '/priorities', [
            'headers' => [
                'Authorization' => $client->getAuth()
            ]
        ]);

        return new Response\Priorities($res);
    }
}
