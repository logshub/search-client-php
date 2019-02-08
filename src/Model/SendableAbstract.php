<?php
namespace Logshub\SearchClient\Model;

abstract class SendableAbstract
{
    /**
     * @return array
     */
    abstract public function toApiArray();

    /**
     * @return bool
     */
    abstract public function isValid();

    /**
     * @param string $value
     * @return string
     */
    public function clear($value)
    {
        return \strip_tags(\str_replace(
            ['"', "'", '`', '<', '>', '–'],
            ['','','','','',''],
            $value
        ));
    }
}
