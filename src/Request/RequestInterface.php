<?php
namespace Logshub\SearchClient\Request;

interface RequestInterface
{
    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return \Logshub\SearchClient\Response\ResponseAbstract
     */
    public function send(\Logshub\SearchClient\Client $client);
}
