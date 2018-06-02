<?php namespace TheHappyCat\NumericTools;

/**
 * Class NumberValidations
 * @package TheHappyCat\NumericTools
 */
class NumberValidations
{
    /**
     * Function that validates if a string contains a valid integer.
     *
     * Different approaches like filter_var($int, FILTER_VALIDATE_INT) will fail with large numbers.
     *
     * An integer is a number that can be written without a fractional component.
     * For example, 21, 4, 0, and -2048 are integers, while 9.75,  5 1⁄2, and √2 are not.
     *
     * @param string $value
     * @return bool
     */
    public static function stringIsInteger(string $string)
    {
        if (!isset($string) || $string === '') {
            return false;
        }

        $chars = str_split($string);

        // If the length of the string is greater than 1, the first digit can't be a zero.

        if (sizeof($chars) > 1 && $chars[0] === '0') {
            return false;
        }

        // If the length of the string is greater than 1, the first digit can be a minus.

        if (sizeof($chars) > 1 && $chars[0] === '-') {
            array_shift($chars);

            // If, after getting rid of the minus, the length of the string is greater or equal than 1, the first digit can't be a 0.

            if (sizeof($chars) >= 1 && $chars[0] === '0') {
                return false;
            }
        }

        $digits = range(0, 9, 1);

        foreach ($chars as $currentChar) {
            $found = false;

            foreach ($digits as $currentDigit) {
                if ($currentChar === "" . $currentDigit) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return false;
            }
        }

        return true;
    }
}
