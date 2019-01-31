<?php

use Logshub\SearchClient\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;

trait ClientTrait
{
    private $url = 'https://uk01.apisearch.logshub.com';
    private $apiHash = 'xizinISDPH';
    private $apiSecret = 'ZJLnvZAujIBOTiJVyfImZYLEKdesJPKUntfFztYLiefQsCMgmg';

    protected function getClient(MockHandler $mockhandler)
    {
        $handler = HandlerStack::create($mockhandler);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handler]);

        return new Client(
            $httpClient,
            $this->url,
            $this->apiHash,
            $this->apiSecret
        );
    }
}
