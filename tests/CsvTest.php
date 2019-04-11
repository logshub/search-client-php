<?php
declare(strict_types=1);

final class CsvTest extends \PHPUnit\Framework\TestCase
{
    public function testCsvRow()
    {
        $path = dirname(__FILE__) . '/test-documents.csv';
        $csv = new \Logshub\SearchClient\Csv\File($path);
        $rows = $csv->getRows();
        $firstRow = $rows[0];
        
        $this->assertInstanceOf(\Logshub\SearchClient\Csv\Row::class, $firstRow);
        $this->assertNotEquals('id', $firstRow->getId());
    }
}
