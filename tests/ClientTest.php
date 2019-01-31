<?php
declare(strict_types=1);
include_once('ClientTrait.php');

use Logshub\SearchClient\Client;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

final class ClientTest extends \PHPUnit\Framework\TestCase
{
    use ClientTrait;
    
    public function testClient()
    {
        $client = $this->getClient(new MockHandler([]));
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals(true, $client->isValid());
    }
}
