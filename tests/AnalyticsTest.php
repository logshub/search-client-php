<?php
declare(strict_types=1);
include_once('ClientTrait.php');

use PHPUnit\Framework\TestCase;
use Logshub\SearchClient\Client;
use Logshub\SearchClient\Model\Product;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

final class AnalyticsTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;

    public function testAnalyticsRequestResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '
{
"total_searches":8,
"total_users":1,
"no_results_rate":0.12,
"top_phrases":[{"name":"pi","total":3},{"name":"raspb","total":2},{"name":"wtf","total":2}],
"top_zero_phrases":[{"name":"ardui","total":3},{"name":"zero","total":2}],
"top_countries":[{"name":"US","total":8}, {"name":"PL","total":2}],
"top_cities":[{"name":"Rzeszow","total":7}]
}
')
        ]);
        $client = $this->getClient($mockHandler);

        $request = new \Logshub\SearchClient\Request\Analytics(
            '1b156083-17fa-45cf-608a-482a9a633a0e',
            new \DateTime('2018-01-01T15:03:01.012345Z')
        );
        $response = $client->getAnalytics($request);

        $this->assertInstanceOf(\Logshub\SearchClient\Response\Analytics::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(8, $response->getTotalSearches());
        $this->assertEquals(1, $response->getTotalUsers());
        $this->assertEquals(0.12, $response->getNoResultsRate());
        
        $this->assertEquals(3, count($response->getTopPhrases()));
        $this->assertInstanceOf(\Logshub\SearchClient\Model\AnalyticsResult::class, $response->getTopPhrases()[0]);
        $this->assertEquals('pi', $response->getTopPhrases()[0]->getName());
        $this->assertEquals(3, $response->getTopPhrases()[0]->getTotal());
        $this->assertEquals('raspb', $response->getTopPhrases()[1]->getName());
        $this->assertEquals(2, $response->getTopPhrases()[1]->getTotal());
        
        $this->assertEquals(2, count($response->getTopZeroPhrases()));
        $this->assertInstanceOf(\Logshub\SearchClient\Model\AnalyticsResult::class, $response->getTopZeroPhrases()[0]);
        $this->assertEquals('ardui', $response->getTopZeroPhrases()[0]->getName());
        $this->assertEquals(3, $response->getTopZeroPhrases()[0]->getTotal());
        $this->assertEquals('zero', $response->getTopZeroPhrases()[1]->getName());
        $this->assertEquals(2, $response->getTopZeroPhrases()[1]->getTotal());
        
        $this->assertEquals(2, count($response->getTopCountries()));
        $this->assertInstanceOf(\Logshub\SearchClient\Model\AnalyticsResult::class, $response->getTopCountries()[0]);
        $this->assertEquals('US', $response->getTopCountries()[0]->getName());
        $this->assertEquals(8, $response->getTopCountries()[0]->getTotal());
        $this->assertEquals('PL', $response->getTopCountries()[1]->getName());
        $this->assertEquals(2, $response->getTopCountries()[1]->getTotal());
        
        $this->assertEquals(1, count($response->getTopCities()));
        $this->assertInstanceOf(\Logshub\SearchClient\Model\AnalyticsResult::class, $response->getTopCities()[0]);
        $this->assertEquals('Rzeszow', $response->getTopCities()[0]->getName());
        $this->assertEquals(7, $response->getTopCities()[0]->getTotal());
    }
}
