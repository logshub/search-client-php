<?php
namespace Logshub\SearchClient\Model;

class Category extends SendableAbstract
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
    
    public function setId($id)
    {
        $this->id = $this->clear($id);
    }
    public function getId()
    {
        return $this->clear($this->id);
    }
    public function setName($name)
    {
        $this->name = $this->clear($name);
    }
    public function getName()
    {
        return $this->clear($this->name);
    }
    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function setUrlImage($urlImage)
    {
        $this->urlImage = $urlImage;
    }
    public function getUrlImage()
    {
        return $this->urlImage;
    }
    public function setDescription($desc)
    {
        $this->description = $this->clear($desc);
    }
    public function getDescription()
    {
        return $this->clear($this->description);
    }
    public function addCategory($name)
    {
        $this->categories[] = $name;
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
