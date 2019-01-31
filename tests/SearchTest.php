<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Logshub\SearchClient\Client;
use Logshub\SearchClient\Model\Product;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

final class SearchTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testSearchRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '
{
    "products": {
        "total":2,
        "docs":
        [
            {
                "id":"2543",
                "name":"Raspberry Pi Model B+ 512MB RAM",
                "url":"/prod.html",
                "price":0
            },
            {
                "id":"5576",
                "name":"Raspberry Pi 3 model B WiFi Bluetooth 1GB RAM 1,2GHz",
                "url":"/prod.html",
                "price":0
            }
        ],
        "agg_categories":
        [
            {"key":"Raspberry Pi","value":6},
            {"key":"Raspberry Pi 2 B+ A+","value":1}
        ]
    },
    "categories": {
        "docs": []
    }
}')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\SearchProducts(
            'sDZTiXX',
            'laptop',
            'products,aggcategories'
        );
        $response = $client->searchProducts($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\SearchProducts::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, $response->getProductsTotal());
        $this->assertEquals(2, count($response->getProducts()));
        $this->assertInstanceOf(\Logshub\SearchClient\Model\Product::class, $response->getProducts()[0]);
        $this->assertEquals('Raspberry Pi Model B+ 512MB RAM', $response->getProducts()[0]->getName());
        $this->assertEquals(2, count($response->getProductAggregations()));
        $this->assertEquals('Raspberry Pi', $response->getProductAggregations()[0]['key']);
        $this->assertEquals(6, $response->getProductAggregations()[0]['value']);
    }
}
