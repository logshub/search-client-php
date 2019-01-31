<?php
namespace Logshub\SearchClient\Model;

class Category
{
    protected $id;
    protected $name;
    protected $url;
    protected $urlImage;
    protected $description;
    protected $categories;

    public function __construct($id, array $params)
    {
        $this->id = $id;
        foreach ($params as $k => $v) {
            if (\property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    public function isValid()
    {
        return $this->getId() && $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function getUrlImage()
    {
        return $this->urlImage;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getCategories()
    {
        return $this->categories;
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
        if ($this->getUrlImage()) {
            $params['url_image'] = $this->getUrlImage();
        }
        if ($this->getDescription()) {
            $params['description'] = $this->getDescription();
        }
        if ($this->getCategories()) {
            $params['categories'] = $this->getCategories();
        }

        return $params;
    }
}
