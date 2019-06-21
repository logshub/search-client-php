<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class DocumentPriority implements RequestInterface
{
    /**
     * uuid of service
     * @var string
     */
    protected $serviceId;
    /**
     * @var string
     */
    protected $documentId;
    /**
     * @var int
     */
    protected $priority = 1;

    /**
     * @param string $serviceId UUID of service
     * @param string $documentId id of a document
     * @param int $priority 1-10 number (1 - no special priority, 10 - very important product/category)
     */
    public function __construct($serviceId, $documentId, $priority)
    {
        $this->serviceId = $serviceId;
        $this->documentId = $documentId;
        $this->priority = (int)$priority;
    }

    /**
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function isValid()
    {
        if (!$this->serviceId || !$this->documentId) {
            throw new \InvalidArgumentException('Service ID or document ID are not valid');
        }
        if ($this->priority < 1 || $this->priority > 10) {
            throw new \InvalidArgumentException('Priority is not valid');
        }

        return true;
    }

    /**
     *
     * @param \Logshub\SearchClient\Client $client
     * @return \Logshub\SearchClient\Response\IndexProducts
     * @throws \InvalidArgumentException
     */
    public function send(\Logshub\SearchClient\Client $client)
    {
        // throws exception in case of validation failure
        $this->isValid();
        
        $res = $client->getHttpClient()->request('PUT', $client->getUrl() . '/v1/service/' . $this->serviceId . '/document/priority', [
            'body' => json_encode([
                'doc_id' => $this->documentId,
                'priority' => $this->priority,
            ]),
            'headers' => [
                'Authorization' => $client->getAuth(),
                'Content-Type' => 'application/json',
            ]
        ]);

        return new Response\DocumentPriority($res);
    }
}
