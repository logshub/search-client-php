<?php
declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class IndexTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testIndexRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{"status":"OK","ack":2}'),
            new Response(400, [], '{"status":"ERROR"}')
        ]);
        $client = $this->getClient($mockHandler);

        $product1 = new \Logshub\SearchClient\Model\Product(1, [
            'name' => 'Gaming Laptop',
            'price' => 123
        ]);

        $product2 = new \Logshub\SearchClient\Model\Product(2, [
            'name' => 'Gaming Laptop i5',
            'price' => 124
        ]);

        $request = new \Logshub\SearchClient\Request\IndexProducts('68825d5e-0614-448a-4e64-413ca24c2062', [
            $product1,
            $product2,
        ]);
        $response = $client->indexProducts($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\IndexProducts::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, $response->getAck());
        $this->assertEquals(true, $response->isSuccessful());
        
        $this->expectException(\Logshub\SearchClient\Exception::class);
        $client->indexProducts($request);
    }
}
