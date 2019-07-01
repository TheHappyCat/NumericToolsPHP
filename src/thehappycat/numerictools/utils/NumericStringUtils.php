<?php namespace TheHappyCat\NumericTools\Utils;

/**
 * Class NumericStringUtils
 * @package TheHappyCat\NumericTools\Utils
 */
class NumericStringUtils
{
    /**
     * @param string $numericString
     * @return string
     */
    public static function purgeZeros(string $numericString)
    {
        if ($numericString[0] === '0') {
            for ($i = 0; $i < strlen($numericString) - 1; $i++) {
                if ($numericString[$i] !== '0') {
                    break;
                }
            }

            $numericString = substr_replace($numericString, '', 0, $i);
        }

        return $numericString;
    }
}
