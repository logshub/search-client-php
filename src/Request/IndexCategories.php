<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class IndexCategories implements RequestInterface
{
    /**
     * uuid of service
     * @var string
     */
    protected $serviceId;

    /**
     * @var array
     */
    protected $categories;

    /**
     *
     * @param string $serviceId UUID of service
     * @param array $categories Array of \Logshub\SearchClient\Model\Category objects
     */
    public function __construct($serviceId, array $categories)
    {
        $this->serviceId = $serviceId;
        $this->categories = $categories;
    }

    /**
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function isValid()
    {
        foreach ($this->categories as $category) {
            if (!$category instanceof \Logshub\SearchClient\Model\Category) {
                throw new \InvalidArgumentException('Category to index is not the correct type');
            }
            if (!$category->isValid()) {
                throw new \InvalidArgumentException('Category to index is not valid');
            }
        }

        return true;
    }

    /**
     *
     * @param \Logshub\SearchClient\Client $client
     * @return \Logshub\SearchClient\Response\IndexCategories
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
        foreach ($this->categories as $category) {
            $params['docs'][] = $category->toApiArray();
        }

        $res = $client->getHttpClient()->request('POST', $client->getUrl() . '/v1/products/categories', [
            'body' => json_encode($params),
            'headers' => [
                'Authorization' => $client->getAuth(),
                'Content-Type' => 'application/json',
            ]
        ]);

        return new Response\IndexCategories($res);
    }
}
