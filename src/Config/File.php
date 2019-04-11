<?php
namespace Logshub\SearchClient\Config;

use Logshub\SearchClient\Exception;

class File
{
    protected $filePath;

    protected $serviceId;
    protected $location;
    protected $apiHash;
    protected $apiSecret;
    protected $pubKey;

    /**
     * @throws Exception
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->validate();
        $this->load();
    }

    public function load()
    {
        $config = \parse_ini_file($this->filePath);
        if (empty($config['serviceid']) || empty($config['location']) || empty($config['apihash']) || empty($config['apisecret'])) {
            throw new Exception('Config file does not contain required keys. See documentation.');
        }

        $this->serviceId = $config['serviceid'];
        $this->location = $config['location'];
        $this->apiHash = $config['apihash'];
        $this->apiSecret = $config['apisecret'];
        if (!empty($config['pub_key'])){
            $this->pubKey = $config['pub_key'];
        }
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }
    
    public function getApiHash()
    {
        return $this->apiHash;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    public function getPubKey()
    {
        return $this->pubKey;
    }

    public function toArray()
    {
        return [
            'serviceid' => $this->getServiceId(),
            'location' => $this->getLocation(),
            'apihash' => $this->getApiHash(),
            'apisecret' => $this->getApiSecret(),
            'pub_key' => $this->getPubKey(),
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function validate()
    {
        if (!\is_file($this->filePath)) {
            throw new Exception('Config file does not exists');
        }
        if (!\is_readable($this->filePath)) {
            throw new Exception('Config file is not readable');
        }

        return true;
    }
}