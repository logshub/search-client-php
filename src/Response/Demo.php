<?php
namespace Logshub\SearchClient\Response;

class Demo extends ResponseAbstract
{
    /**
     * Returns how many documents has ben acknowledged
     *
     * @return int
     */
    public function getAck()
    {
        return $this->getBodyField('ack');
    }
}
