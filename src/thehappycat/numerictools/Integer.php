<?php namespace TheHappyCat\NumericTools;

use Exception;

/**
 * Class Integer
 * @package TheHappyCat\NumericTools
 */
class Integer extends Number
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
     * @return \TheHappyCat\NumericTools\Integer
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
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public static function createByString(string $value)
    {
        $instance = new self();
        $instance->setStringValue($value);
        return $instance;
    }

    /**
     * @param string $value
     * @throws Exception
     */
    public function setStringValue(string $value)
    {
        if (!NumberValidations::stringIsInteger($value)) {
            throw new Exception(
                sprintf('The given value is not a valid number: %s', $value)
            );
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
     * @return bool
     */
    public function isZero()
    {
        if (sizeof($this->value) === 1) {
            if ($this->value[0] === 0) {
                return true;
            }
        }

        return false;
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
     * @return int
     */
    public function getLength()
    {
        return strlen($this->getStringValue());
    }

    /**
     * @param Integer $number
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
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
                } else {
                    $carry = intval($stringResult[0]);
                    $subResult = intval($stringResult[1]);
                }
            } else {
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
        } else {
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
                
                $numberLength = sizeof($this->value);
                
                for ($i = 1; $i < $numberLength; $i++) {
                    $a0 = $this->value[$i - 1];
                    $a = $this->value[$i];

                    $b0 = $number->value[$i - 1];
                    $b = $number->value[$i];

                    if ($a0 === $b0 && $a < $b) {
                        return false;
                    }

                    if ($a === 0 && ($a0 >= $b0 || $a0 === 0)) {
                        continue;
                    }

                    if ($a > $b) {
                        return true;
                    }
                }
            } else {
                return $comparison === 1;
            }
        }
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function subtract(Integer $number)
    {
        // e.g. -8 - (-4) = -8 + 4 = -4
        if ($this->isNegative() && $number->isNegative()) {
            // @todo
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
            // @todo
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
            } else {
                $currentBottom = $bottom[$i - $indexDiff];

                if ($currentTop - $carry >= $currentBottom) {
                    $intResult = $currentTop - $carry - $currentBottom;
                    $carry = 0;
                } else {
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
                } else {
                    $carry = intval($stringResult[0]);
                    $subResult = intval($stringResult[1]);
                }
            } else {
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
    private function purgeZeros(string $numericString)
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
    public function getMaximumMultiplier(Integer $divisor)
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
            } else {
                $multiplier = $multiplier->add(
                    Integer::createByInt(1)
                );
            }
        }
        while (true);
    }

    /**
     * @param \TheHappyCat\NumericTools\Integer $divisor
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function mod(Integer $divisor)
    {
        return $this->divideBy($divisor, true);
    }

    /**
     * @param Integer $divisor
     * @param bool $modMode
     * @return Integer
     * @throws Exception
     */
    public function divideBy(Integer $divisor, $modMode = false)
    {
        if ($divisor->isZero()) {
            throw new Exception("Can't divide by zero!");
        }

        if ($this->isZero()) {
            return Integer::createByInt(0);
        }

        if ($divisor->greaterThan($this)) {
            throw new Exception(sprintf('Operation currently not supported: %s > %s', $divisor, $this));
        }

        $stringResult = '';
        $currentIndex = 0;

        $currentSelection = Integer::createByString(
            implode('', array_slice($this->value, $currentIndex, $divisor->getLength()))
        );

        if (!$currentSelection->greaterOrEqualTo($divisor)) {
            $currentSelection = Integer::createByString(
                implode('', array_slice($this->value, $currentIndex, $divisor->getLength() + 1))
            );

            $currentIndex = $divisor->getLength() + 1;
        } else {
            $currentIndex = $divisor->getLength();
        }

        $maxMultiplier = $currentSelection->getMaximumMultiplier($divisor);
        $stringResult .= $maxMultiplier->getStringValue();
        $multiplication = $maxMultiplier->multiplyBy($divisor);
        $remainder = $currentSelection->subtract($multiplication);

        while ($currentIndex < $this->getLength()) {
            $currentSelection = Integer::createByString(
                $this->purgeZeros(
                    $remainder->getStringValue() . implode('', array_slice($this->value, $currentIndex, 1))
                )
            );

            if (!$currentSelection->greaterOrEqualTo($divisor)) {
                $stringResult .= '0';

                $currentIndex++;

                $remainder = $currentSelection;
                continue;
            }

            $currentIndex++;

            $maxMultiplier = $currentSelection->getMaximumMultiplier($divisor);
            $stringResult .= $maxMultiplier->getStringValue();
            $multiplication = $maxMultiplier->multiplyBy($divisor);
            $remainder = $currentSelection->subtract($multiplication);
        }

        if ($modMode) {
            return $remainder;
        }

        return Integer::createByString($stringResult);
    }
}
