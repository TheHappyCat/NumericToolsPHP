<?php namespace TheHappyCat\NumericTools;

use Exception;

/**
 * Class Integer
 * @package TheHappyCat\NumericTools
 */
class Integer
{
    /**
     * @var array
     */
    private $value = [];

    /**
     * @var bool
     */
    private $negative = false;

    /**
     * @param int $value
     */
    public static function createByInt(int $value)
    {
        $instance = new self();
        $instance->setIntValue($value);
        return $instance;
    }

    /**
     * @param int $value
     */
    public function setIntValue(int $value)
    {
        if ($value < 0) {
            $this->negative = true;

            $value = abs($value);
        }

        $this->value = array_map('intval', str_split($value));
    }

    /**
     * @param string $value
     */
    public static function createByString(string $value)
    {
        $instance = new self();
        $instance->setStringValue($value);
        return $instance;
    }

    /**
     * @param string $value
     */
    public function setStringValue(string $value)
    {
        if (!NumberValidations::stringIsInteger($value)) {
            throw new Exception('The given value is not a valid number');
        }

        $parts = str_split($value);

        if ($parts[0] === '-') {
            $this->negative = true;

            array_shift($parts);

            $value = implode('', $parts);
        }

        $this->value = array_map('intval', str_split($value));
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return $this->negative;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->isNegative() ? '-' : '') . implode('', $this->value);
    }

    /**
     * @return string
     */
    public function getStringValue()
    {
        return $this->__toString();
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return \TheHappyCat\NumericTools\Integer
     */
    public function add(Integer $number)
    {
        $comparison = sizeof($this->value) <=> sizeof($number->value);

        $top = $comparison === 0 ? $this->value : ($comparison === -1 ? $number->value : $this->value);
        $bottom = $comparison === 0 ? $number->value : ($comparison === -1 ? $this->value : $number->value);

        $indexDiff = sizeof($top) - sizeof($bottom);

        $stringHolder = '';

        $carry = 0;

        for ($i = sizeof($top) - 1; $i >= 0; $i--) {
            $intResult = ($i - $indexDiff) < 0 ? ($top[$i] + $carry) : ($top[$i] + $bottom[$i - $indexDiff] + $carry);

            $stringResult = (string) $intResult;

            if (strlen($stringResult) === 2) {
                if ($i === 0) {
                    $carry = 0;
                    $subResult = $intResult;
                }
                else {
                    $carry = intval($stringResult[0]);
                    $subResult = intval($stringResult[1]);
                }
            }
            else {
                $carry = 0;
                $subResult = intval($stringResult[0]);
            }

            $stringHolder = $subResult . $stringHolder;
        }

        return Integer::createByString($stringHolder);
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return bool
     */
    public function greaterOrEqualTo(Integer $number)
    {
        if ($this->greaterThan($number)) {
            return true;
        }
        else {
            return ($this->getStringValue() === $number->getStringValue());
        }
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return bool
     */
    public function greaterThan(Integer $number)
    {
        if (!empty($number)) {
            $comparison = sizeof($this->value) <=> sizeof($number->value);

            // if the length of both numbers is the same
            if ($comparison === 0) {
                // if both numbers are equal
                if ($this->getStringValue() === $number->getStringValue()) {
                    return false;
                }

                // if the first digit of the current number is greater than the first digit of the other number
                if ($this->value[0] > $number->value[0]) {
                    return true;
                }

                // if the first digit of the current number is less than the first digit of the other number
                if ($this->value[0] < $number->value[0]) {
                    return false;
                }

                // both numbers have the same length, they are not equal and their first digit is equal

                for ($i = 1; $i < sizeof($this->value); $i++) {
                    if ($this->value[$i] < $number->value[$i]) {
                        return false;
                    }
                }

                // same length, not equal, first digit equal and current number greater than the other number
                return true;
            }
            // if the length of both numbers is not the same
            else {
                return $comparison === 1;
            }
        }
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return \TheHappyCat\NumericTools\Integer
     */
    public function subtract(Integer $number)
    {
        // e.g. -8 - (-4) = -8 + 4 = -4
        if ($this->isNegative() && $number->isNegative()) {

        }

        // e.g. 8 - (-4) = 8 + 4 = 12
        if (!$this->isNegative() && $number->isNegative()) {
            $positiveNumber = Integer::createByString(
                implode('', $number->value)
            );

            return $this->add($positiveNumber);
        }

        // e.g. -8 - 4 = -12
        if ($this->isNegative() && !$number->isNegative()) {

        }

        $thisGreaterOrEqual = $this->greaterOrEqualTo($number);

        $negative = $thisGreaterOrEqual ? false : true;

        $top = $thisGreaterOrEqual ? $this->value : $number->value;
        $bottom = $thisGreaterOrEqual ? $number->value : $this->value;

        $indexDiff = sizeof($top) - sizeof($bottom);

        $carry = 0;

        $stringHolder = '';

        for ($i = sizeof($top) - 1; $i >= 0; $i--) {
            $currentTop = $top[$i];

            if (($i - $indexDiff) < 0) {
                $intResult = $currentTop - $carry < 0 ? 9 : $currentTop - $carry;
                $carry = $currentTop - $carry < 0 ? 1 : 0;
            }
            else {
                $currentBottom = $bottom[$i - $indexDiff];

                if ($currentTop - $carry >= $currentBottom) {
                    $intResult = $currentTop - $carry - $currentBottom;
                    $carry = 0;
                }
                else {
                    $intResult = $currentTop - $carry < 0 ? 9 - $currentBottom : intval('1' . ($currentTop - $carry)) - $currentBottom;
                    $carry = 1;
                }
            }

            $stringHolder = $intResult . $stringHolder;
        }

        return Integer::createByString(
            $negative ? '-' . $this->purgeZeros($stringHolder) : $this->purgeZeros($stringHolder)
        );
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function multiplyBy(Integer $number)
    {
        $comparison = sizeof($this->value) <=> sizeof($number->value);

        $top = $comparison === 0 ? $this : ($comparison === -1 ? $number : $this);
        $bottom = $comparison === 0 ? $number->value : ($comparison === -1 ? $this->value : $number->value);

        $result = Integer::createByInt(0);

        for ($i = sizeof($bottom) - 1; $i >= 0; $i--) {
            $delta = sizeof($bottom) - 1 - $i;

            $subResult = $top->multiplyByInt($bottom[$i]);

            $subResultWithZeros = $this->purgeZeros($subResult->getStringValue() . str_repeat('0', $delta));

            $result = $result->add(
                Integer::createByString($subResultWithZeros)
            );
        }

        return $result;
    }

    /**
     * @param int $number
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function multiplyByInt(int $number)
    {
        if ($number < 0 || $number > 9) {
            throw new Exception('The number can not be lower than 0 or greater than 9');
        }

        $stringHolder = '';

        $carry = 0;

        for ($i = sizeof($this->value) - 1; $i >= 0; $i--) {
            $intResult = ($number * $this->value[$i]) + $carry;

            $stringResult = (string) $intResult;

            if (strlen($stringResult) === 2) {
                if ($i === 0) {
                    $carry = 0;
                    $subResult = $intResult;
                }
                else {
                    $carry = intval($stringResult[0]);
                    $subResult = intval($stringResult[1]);
                }
            }
            else {
                $carry = 0;
                $subResult = intval($stringResult[0]);
            }

            $stringHolder = $subResult . $stringHolder;
        }

        return Integer::createByString(
            $this->purgeZeros($stringHolder)
        );
    }

    /**
     * @param string $numericString
     * @return string
     */
    private function purgeZeros($numericString)
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

    /**
     * @param \TheHappyCat\NumericTools\Integer $divisor
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function getMaximumMultiplier($divisor)
    {
        if (!$this->greaterOrEqualTo($divisor)) {
            throw new Exception('The current number (dividend) must be greater or equal to the divisor');
        }

        $multiplier = Integer::createByInt(1);

        do {
            $multiplication = $divisor->multiplyBy($multiplier);

            if (strval($multiplication) === strval($this)) {
                return $multiplier;
            }

            if ($multiplication->greaterThan($this)) {
                return $multiplier->subtract(
                    Integer::createByInt(1)
                );
            }
            else {
                $multiplier = $multiplier->add(
                    Integer::createByInt(1)
                );
            }
        }
        while (true);
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $divisor
     */
    public function divide($divisor)
    {

    }
}
