<?php
namespace Logshub\SearchClient\Model;

class AnalyticsResult
{
    protected $name;
    protected $total;

    public function __construct($name, $total)
    {
        $this->name = $name;
        $this->total = $total;
    }
    
    public function getName($default = '')
    {
        return $this->name ? $this->name : $default;
    }
    
    public function getTotal($default = 0)
    {
        return $this->total ? $this->total : $default;
    }
}
