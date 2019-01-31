<?php
declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class DeleteTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testDemoRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{"status":"OK","ack":6}')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\Delete(
            '68825d5e-0614-448a-4e64-413ca24c2062',
            '123'
        );
        $response = $client->deleteDocument($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\Delete::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
