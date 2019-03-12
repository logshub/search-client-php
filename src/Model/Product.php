<?php
namespace Logshub\SearchClient\Model;

class Product extends SendableAbstract
{
    protected $id;
    protected $name;
    protected $url;
    protected $price;
    protected $currency;
    protected $urlImage;
    protected $description;
    protected $categories;
    protected $sku;
    protected $headline;
    protected $availibility;
    protected $reviewScore;
    protected $reviewCount;
    protected $priceOld;

    public function __construct($id, array $params)
    {
        $this->id = $id;
        foreach ($params as $k => $v) {
            if (\property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    public function addCategory($name)
    {
        $this->categories[] = $name;
    }

    public function setName($name)
    {
        $name = $this->clear($name);
        if ($name) {
            // cutting name, as more than 130 chars are not allowed
            $name = substr($name, 0, 130);
        }

        $this->name = $name;
    }

    public function isValid()
    {
        return $this->getId() && $this->getName();
    }
    public function getId()
    {
        return $this->clear($this->id);
    }
    /**
     * TODO: the same code as setter
     */
    public function getName()
    {
        $name = $this->clear($this->name);
        if ($name) {
            // cutting name, as more than 130 chars are not allowed
            $name = substr($name, 0, 130);
        }

        return $name;
    }
    public function getUrl()
    {
        if (!$this->url) {
            return '';
        }
        if (substr($this->url, 0, 1) !== '/' && substr($this->url, 0, 4) !== 'http') {
            return '/' . $this->url;
        }

        return $this->url;
    }
    public function getPrice()
    {
        return (float)$this->price;
    }
    public function getCurrency()
    {
        return $this->currency;
    }
    public function getUrlImage()
    {
        return $this->urlImage;
    }
    public function getDescription()
    {
        return $this->clear($this->description);
    }
    public function getCategories()
    {
        return $this->categories;
    }
    public function getSku()
    {
        return $this->sku;
    }
    public function getHeadline()
    {
        return $this->headline;
    }
    public function getAvailibility()
    {
        return $this->availibility;
    }
    public function getReviewScore()
    {
        return $this->reviewScore;
    }
    public function getReviewCount()
    {
        return $this->reviewCount;
    }
    public function getPriceOld()
    {
        return (float)$this->priceOld;
    }

    /**
     * @return array
     */
    public function toApiArray()
    {
        $params = [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
        if ($this->getUrl()) {
            $params['url'] = $this->getUrl();
        }
        if ($this->getPrice()) {
            $params['price'] = $this->getPrice();
        }
        if ($this->getCurrency()) {
            $params['currency'] = $this->getCurrency();
        }
        if ($this->getUrlImage()) {
            $params['url_image'] = $this->getUrlImage();
        }
        if ($this->getDescription()) {
            $params['description'] = $this->getDescription();
        }
        if ($this->getCategories()) {
            $params['categories'] = $this->getCategories();
        }
        if ($this->getSku()) {
            $params['sku'] = $this->getSku();
        }
        if ($this->getHeadline()) {
            $params['headline'] = $this->getHeadline();
        }
        if ($this->getAvailibility()) {
            $params['availibility'] = (int)$this->getAvailibility();
        }
        if ($this->getReviewScore()) {
            $params['review_score'] = (float)$this->getReviewScore();
        }
        if ($this->getReviewCount()) {
            $params['review_count'] = (int)$this->getReviewCount();
        }
        if ($this->getPriceOld()) {
            $params['price_old'] = $this->getPriceOld();
        }

        return $params;
    }
}
