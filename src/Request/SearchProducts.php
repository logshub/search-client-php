<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class SearchProducts implements RequestInterface
{
    /**
     * @var string
     */
    protected $pubKey;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $features;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $limitCategories;

    public function __construct($pubKey, $query, $features = 'products,aggcategories', $limit = 10, $limitCategories = 2)
    {
        $this->pubKey = $pubKey;
        $this->query = $query;
        $this->features = $features;
        $this->limit = $limit;
        $this->limitCategories = $limitCategories;
    }

    public function isValid()
    {
        return $this->pubKey && $this->query && $this->features;
    }

    public function send(\Logshub\SearchClient\Client $client)
    {
        $params = [
            'pub_key' => $this->pubKey,
            'q' => $this->query,
            'features' => $this->features,
            'limit' => $this->limit,
            'limit_cat' => $this->limitCategories,
        ];

        $res = $client->getHttpClient()->request('GET', $client->getUrl() . '/v1/products/search?' . http_build_query($params), []);

        return new Response\SearchProducts($res);
    }
}
