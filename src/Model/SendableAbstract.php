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
        $defaultCharsToRemove = ['"', "'", '`', '<', '>', '–', '´', '™', '®'];
        return \strip_tags(\str_replace(
            $defaultCharsToRemove,
            array_fill(0, count($defaultCharsToRemove), ''),
            $value
        ));
    }
}
