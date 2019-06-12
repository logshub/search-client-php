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
        $value = \strip_tags(\str_replace(
            $defaultCharsToRemove,
            array_fill(0, count($defaultCharsToRemove), ''),
            $value
        ));

        return $value;

        // TODO: fix it
        // removing special chars
        // https://www.utf8-chartable.de/unicode-utf8-table.pl
        // $chars = \str_split($value);
        // foreach ($chars as $k => $char){
        //     if (!$char){
        //         continue;
        //     }
        //     list(, $ord) = \unpack('N', $char); // \mb_convert_encoding($char, 'UCS-4BE', 'UTF-8')
        //     var_dump($char, $ord);
        //     if ($ord && $ord >= 161 && $ord <= 191){
        //         $chars[$k] = '';
        //     }
        // }

        // return \implode('', $chars);
    }
}
