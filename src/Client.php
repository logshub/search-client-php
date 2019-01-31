<?php
namespace Logshub\SearchClient;

use GuzzleHttp\ClientInterface;

class Client
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $apiHash;
    /**
     * @var string
     */
    protected $apiSecret;

    /**
     *
     * @param ClientInterface $client
     * @param string $url
     * @param string $apiHash
     * @param string $apiSecret
     */
    public function __construct(\GuzzleHttp\ClientInterface $client, $url, $apiHash, $apiSecret)
    {
        $this->client = $client;
        $this->url = $url;
        $this->apiHash = $apiHash;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getAuth()
    {
        return $this->apiHash . ', ' . $this->apiSecret;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        // TODO: better validation
        return $this->url && $this->apiHash && $this->apiSecret;
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\IndexProducts $request
     * @return Response\IndexProducts
     */
    public function indexProducts(Request\IndexProducts $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\IndexCategories $request
     * @return Response\IndexCategories
     */
    public function indexCategories(Request\IndexCategories $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\SearchProducts $request
     * @return Response\SearchProducts
     */
    public function searchProducts(Request\SearchProducts $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\Delete $request
     * @return Response\Delete
     */
    public function deleteDocument(Request\Delete $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\Demo $request
     * @return Response\Demo
     */
    public function indexDemo(Request\Demo $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\Clear $request
     * @return Response\Clear
     */
    public function clearIndex(Request\Clear $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\Analytics $request
     * @return Response\Analytics
     */
    public function getAnalytics(Request\Analytics $request)
    {
        return $this->send($request);
    }

    /**
     *
     * @param \Logshub\SearchClient\Request\RequestInterface $request
     * @return Response\ResponseAbstract
     * @throws Exception
     */
    protected function send(Request\RequestInterface $request)
    {
        try {
            if (!$request->isValid()) {
                throw new Exception('Request is not valid');
            }
            return $request->send($this);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new Exception('Connection exception', 500, $e);
        }
    }

    /**
     *
     * @param string $location
     * @param string $apiHash
     * @param string $apiSecret
     * @return \Logshub\SearchClient\Client
     */
    public static function fromLocation($location, $apiHash, $apiSecret)
    {
        $httpClient = new \GuzzleHttp\Client();

        return new Client(
            $httpClient,
            'https://' . $location . '.apisearch.logshub.com',
            $apiHash,
            $apiSecret
        );
    }
}
