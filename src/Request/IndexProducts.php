<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class IndexProducts implements RequestInterface
{
    /**
     * uuid of service
     * @var string
     */
    protected $serviceId;

    /**
     * @var array
     */
    protected $products;

    /**
     *
     * @param string $serviceId UUID of service
     * @param array $products Array of \Logshub\SearchClient\Model\Product objects
     */
    public function __construct($serviceId, array $products)
    {
        $this->serviceId = $serviceId;
        $this->products = $products;
    }

    /**
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function isValid()
    {
        foreach ($this->products as $product) {
            if (!$product instanceof \Logshub\SearchClient\Model\Product) {
                throw new \InvalidArgumentException('Product to index is not the correct type');
            }
            if (!$product->isValid()) {
                throw new \InvalidArgumentException('Product to index is not valid');
            }
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
        
        $params = [
            'service_id' => $this->serviceId,
            'docs' => [],
        ];
        foreach ($this->products as $product) {
            $params['docs'][] = $product->toApiArray();
        }

        $res = $client->getHttpClient()->request('POST', $client->getUrl() . '/v1/products', [
            'body' => json_encode($params),
            'headers' => [
                'Authorization' => $client->getAuth(),
                'Content-Type' => 'application/json',
            ]
        ]);

        return new Response\IndexProducts($res);
    }
}
