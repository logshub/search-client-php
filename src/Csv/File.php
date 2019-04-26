<?php
namespace Logshub\SearchClient\Csv;

use Logshub\SearchClient\Exception;

class File
{
    protected $filePath;
    protected $csvSeparator;

    /**
     * @throws Exception
     */
    public function __construct($filePath, $csvSeparator = ';')
    {
        $this->filePath = $filePath;
        $this->csvSeparator = $csvSeparator;
        $this->validate();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function validate()
    {
        if (!\is_file($this->filePath)) {
            throw new Exception('CSV file does not exists');
        }
        if (!\is_readable($this->filePath)) {
            throw new Exception('CSV file is not readable');
        }

        return true;
    }

    /**
     * @return array of Row objects
     * @throws Exception
     */
    public function getRows()
    {
        $this->validate();
        if (($handle = fopen($this->filePath, "r")) === false) {
            throw new Exception('Unable to read CSV file');
        }
        $rows = [];
        $counter = 0;
        while (($csvRow = fgetcsv($handle, 0, $this->csvSeparator)) !== false) {
            $counter++;
            // header or empty row
            if ($counter === 1 || empty($csvRow[0])) {
                continue;
            }
            $rows[] = new Row($csvRow);
        }
        fclose($handle);

        return $rows;
    }
}
