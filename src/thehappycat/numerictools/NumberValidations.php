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

        // Handle negative numbers
        if ($chars[0] === '-') {
            array_shift($chars);
            
            // After removing minus, must have at least one digit
            if (sizeof($chars) === 0) {
                return false;
            }
            
            // Reject -0
            if (sizeof($chars) === 1 && $chars[0] === '0') {
                return false;
            }
            
            // If more than one digit, first digit can't be zero
            if (sizeof($chars) > 1 && $chars[0] === '0') {
                return false;
            }
        } else {
            // For positive numbers, if more than one digit, first digit can't be zero
            if (sizeof($chars) > 1 && $chars[0] === '0') {
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
