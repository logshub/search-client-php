<?php
declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class DemoTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testDemoRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{"status":"OK","ack":6}')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\Demo(
            '68825d5e-0614-448a-4e64-413ca24c2062',
            'electronics'
        );
        $response = $client->indexDemo($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\Demo::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(6, $response->getAck());
    }
}
