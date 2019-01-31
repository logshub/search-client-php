<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class Demo implements RequestInterface
{
    /**
     * uuid of service
     * @var string
     */
    protected $serviceId;
    /**
     * Id of the demo set eg. electronics
     * @var string
     */
    protected $demoId;

    public function __construct($serviceId, $demoId)
    {
        $this->serviceId = $serviceId;
        $this->demoId = $demoId;
    }

    public function isValid()
    {
        return true;
    }

    public function send(\Logshub\SearchClient\Client $client)
    {
        $params = [
            'service_id' => $this->serviceId,
            'demo' => $this->demoId,
        ];

        $res = $client->getHttpClient()->request('POST', $client->getUrl() . '/v1/products/demo', [
            'body' => json_encode($params),
            'headers' => [
                'Authorization' => $client->getAuth(),
                'Content-Type' => 'application/json',
            ]
        ]);

        return new Response\Demo($res);
    }
}
