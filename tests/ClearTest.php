<?php
declare(strict_types=1);
include_once('ClientTest.php');

use Logshub\SearchClient\Client;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class ClearRequestsTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testClearRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{"status":"OK"}')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\Clear('68825d5e-0614-448a-4e64-413ca24c2062');
        $response = $client->clearIndex($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\Clear::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
