<?php
declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class DocumentPrioritiesTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testDocPrioritiesRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{
                "docs":[
                    {"id":"28146a09","name":"Jane Chapman","url":"/a/jane-chapman/","price":0,"priority":10},
                    {"id":"a78713f5","name":"Dagmar Von Cramm","url":"/a/dagmar-von-cramm/","price":0,"priority":9},
                    {"id":"bf4d0a6a","name":"Adam Rex","url":"/a/adam-rex/","price":0,"priority":10}
                ],
                "total":3
            }')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\Priorities('68825d5e-0614-448a-4e64-413ca24c2062');
        $response = $client->getPriorities($request);
        $docs = $response->getDocuments();

        $this->assertInstanceOf(\Logshub\SearchClient\Response\Priorities::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, $response->getTotal());
        $this->assertEquals(3, count($docs));
        $this->assertEquals(10, $docs[0]->getPriority());
        $this->assertEquals(9, $docs[1]->getPriority());
    }
}
