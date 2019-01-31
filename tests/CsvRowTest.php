<?php
declare(strict_types=1);

final class CsvRowTest extends \PHPUnit\Framework\TestCase
{
    public function testCsvRow()
    {
        $rowArray = [
            '2',
            'Test Product',
            '/test-product',
            '/test-product.jpg',
            '9.99',
            '19.99',
            'USD',
            'test test test',
            'Movies|History',
            'asd-123'
        ];
        $row = new \Logshub\SearchClient\Csv\Row($rowArray);
        $product = $row->toProduct();
        $category = $row->toCategory();

        $this->assertInstanceOf(\Logshub\SearchClient\Model\Product::class, $product);
        $this->assertInstanceOf(\Logshub\SearchClient\Model\Category::class, $category);
        $this->assertEquals('2', $row->getId());
        $this->assertEquals('Test Product', $row->getName());
        $this->assertEquals('/test-product', $row->getUrl());
        $this->assertEquals('/test-product.jpg', $row->getUrlImage());
        $this->assertEquals('9.99', $row->getPrice());
        $this->assertEquals('19.99', $row->getPriceOld());
        $this->assertEquals('USD', $row->getCurrency());
        $this->assertEquals('test test test', $row->getDescription());
        $this->assertEquals('Movies', $row->getCategories()[0]);
        $this->assertEquals('History', $row->getCategories()[1]);
        $this->assertEquals('asd-123', $row->getSku());
    }
}
