<?php
namespace Logshub\SearchClient\Response;

abstract class ResponseAbstract
{
    /**
     * @return \GuzzleHttp\Psr7\Response
     */
    protected $response;
    /**
     * @var array result of json_decode of body
     */
    protected $responseBodyArray;

    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return array
     */
    public function getBodyArray()
    {
        if ($this->responseBodyArray === null) {
            $this->responseBodyArray = \json_decode($this->response->getBody(), true);
        }

        return $this->responseBodyArray;
    }

    /**
     * @return mixed
     */
    public function getBodyField($name, $default = '')
    {
        $body = $this->getBodyArray();

        return !empty($body[$name]) ? $body[$name] : $default;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->response->getBody()->__toString();
    }
    
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getStatusCode(), [200, 201, 202]);
    }
}
