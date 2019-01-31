<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class Clear implements RequestInterface
{
    /**
     * uuid of service
     * @var string
     */
    protected $serviceId;

    public function __construct($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function isValid()
    {
        // TODO: implement
        return true;
    }

    public function send(\Logshub\SearchClient\Client $client)
    {
        $res = $client->getHttpClient()->request('PUT', $client->getUrl() . '/v1/service/' . $this->serviceId . '/clear', [
            'headers' => [
                'Authorization' => $client->getAuth(),
            ]
        ]);

        return new Response\Clear($res);
    }
}
