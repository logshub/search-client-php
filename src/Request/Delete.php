<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class Delete implements RequestInterface
{
    /**
     * UUID of service
     * @var string
     */
    protected $serviceId;
    /**
     * Id of the document
     * @var string
     */
    protected $documentId;

    /**
     *
     * @param string $serviceId
     * @param string $documentId
     */
    public function __construct($serviceId, $documentId)
    {
        $this->serviceId = $serviceId;
        $this->documentId = $documentId;
    }

    /**
     *
     * @return boolean
     */
    public function isValid()
    {
        return true;
    }

    /**
     *
     * @param \Logshub\SearchClient\Client $client
     * @return \Logshub\SearchClient\Response\Demo
     */
    public function send(\Logshub\SearchClient\Client $client)
    {
        $params = [
            'service_id' => $this->serviceId,
            'doc_id' => $this->documentId,
        ];

        $res = $client->getHttpClient()->request('DELETE', $client->getUrl() . '/v1/document', [
            'body' => json_encode($params),
            'headers' => [
                'Authorization' => $client->getAuth(),
                'Content-Type' => 'application/json',
            ]
        ]);

        return new Response\Delete($res);
    }
}
