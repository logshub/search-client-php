<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Logshub\SearchClient\Client;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

final class ModelProductTest extends \PHPUnit\Framework\TestCase
{
    public function testProduct()
    {
        $product = new \Logshub\SearchClient\Model\Product(1, [
            'name' => 'Gaming Laptop',
            'price' => 123
        ]);
        $product->addCategory('Test Category');
        $product->addCategory('Test Category 2');

        $this->assertEquals(1, $product->getId());
        $this->assertEquals('Gaming Laptop', $product->getName());
        $this->assertEquals(123, $product->getPrice());
        $this->assertEquals(2, count($product->getCategories()));
        $this->assertEquals(true, is_array($product->toApiArray()));
        // TODO: fix it
        // $this->assertEquals('Gaming Laptop', $product->clear("Gaming Laptop©®¶™"));
    }
}
