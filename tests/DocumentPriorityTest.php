<?php
declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class DocumentPriorityTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testDocPriorityRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{"status":"OK"}')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\DocumentPriority('68825d5e-0614-448a-4e64-413ca24c2062', '123', 10);
        $response = $client->setDocumentPriority($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\DocumentPriority::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
