<?php
declare(strict_types=1);

final class ConfigFileTest extends \PHPUnit\Framework\TestCase
{
    public function testFile()
    {
        $path = dirname(__FILE__) . '/test-config.ini';
        $config = new \Logshub\SearchClient\Config\File($path);
        $confArray = $config->toArray();
        
        $this->assertEquals('f4058cb4-e2e1-4eaf-69b2-742374436731', $confArray['serviceid']);
        $this->assertEquals('ZQdzxcIZgb', $confArray['apihash']);
        $this->assertEquals('uk01', $confArray['location']);
        $this->assertEquals('lOsQwdwyMZQYJqfVUJtyhsnrlZTQkiQwfCnAPcypBEXSifSkqi', $confArray['apisecret']);
        $this->assertEquals('vclkaVL', $confArray['pub_key']);

        $this->assertEquals('f4058cb4-e2e1-4eaf-69b2-742374436731', $config->getServiceId());
        $this->assertEquals('ZQdzxcIZgb', $config->getApihash());
        $this->assertEquals('uk01', $config->getLocation());
        $this->assertEquals('lOsQwdwyMZQYJqfVUJtyhsnrlZTQkiQwfCnAPcypBEXSifSkqi', $config->getApiSecret());
        $this->assertEquals('vclkaVL', $config->getPubKey());

        $this->expectException(\Logshub\SearchClient\Exception::class);
        new \Logshub\SearchClient\Config\File('fake-path');
    }
}
