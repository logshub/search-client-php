<?php
namespace Logshub\SearchClient\Csv;

class Row
{
    const IDX_ID = 0;
    const IDX_NAME = 1;
    const IDX_URL = 2;
    const IDX_URL_IMAGE = 3;
    const IDX_PRICE = 4;
    const IDX_PRICE_OLD = 5;
    const IDX_CURRENCY = 6;
    const IDX_DESCRIPTION = 7;
    const IDX_CATEGORIES = 8;
    const IDX_SKU = 9;

    /**
     * order: id,name,url,url_image,price,price_old,currency,description,categories,sku
     * @var array
     */
    protected $row;

    public function __construct(array $csvRow)
    {
        $this->row = $csvRow;
    }

    public function getId()
    {
        return !empty($this->row[self::IDX_ID]) ? $this->row[self::IDX_ID] : '';
    }
    public function getName()
    {
        return !empty($this->row[self::IDX_NAME]) ? $this->row[self::IDX_NAME] : '';
    }
    public function getUrl()
    {
        return !empty($this->row[self::IDX_URL]) ? $this->row[self::IDX_URL] : '';
    }
    public function getUrlImage()
    {
        return !empty($this->row[self::IDX_URL_IMAGE]) ? $this->row[self::IDX_URL_IMAGE] : '';
    }
    public function getPrice()
    {
        return !empty($this->row[self::IDX_PRICE]) ? $this->row[self::IDX_PRICE] : 0;
    }
    public function getPriceOld()
    {
        return !empty($this->row[self::IDX_PRICE_OLD]) ? $this->row[self::IDX_PRICE_OLD] : 0;
    }
    public function getCurrency()
    {
        return !empty($this->row[self::IDX_CURRENCY]) ? $this->row[self::IDX_CURRENCY] : '';
    }
    public function getDescription()
    {
        return !empty($this->row[self::IDX_DESCRIPTION]) ? $this->row[self::IDX_DESCRIPTION] : '';
    }
    public function getCategories()
    {
        if (empty($this->row[self::IDX_CATEGORIES])) {
            return [];
        }
        return \explode('|', $this->row[self::IDX_CATEGORIES]);
    }
    public function getSku()
    {
        return !empty($this->row[self::IDX_SKU]) ? $this->row[self::IDX_SKU] : '';
    }

    public function toCategory()
    {
        return new \Logshub\SearchClient\Model\Category($this->getId(), [
            'name' => $this->getName(),
            'url' => $this->getUrl(),
            'urlImage' => $this->getUrlImage(),
            'description' => $this->getDescription(),
            'categories' => $this->getCategories(),
        ]);
    }

    public function toProduct()
    {
        return new \Logshub\SearchClient\Model\Product($this->getId(), [
            'name' => $this->getName(),
            'url' => $this->getUrl(),
            'urlImage' => $this->getUrlImage(),
            'price' => $this->getPrice(),
            'priceOld' => $this->getPriceOld(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'categories' => $this->getCategories(),
            'sku' => $this->getSku(),
            // TODO: headline, availibility, review_score, review_count
        ]);
    }
}
