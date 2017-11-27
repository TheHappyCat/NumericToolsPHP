<?php namespace TheHappyCat\NumericTools;

use Exception;

/**
 * Class Integer
 * @package TheHappyCat\NumericTools
 */
class Integer
{
    /**
     * @var null
     */
    private $value = null;

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

        $this->value = array_map('intval', str_split($value));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode('', $this->value);
    }

    /**
     * @return string
     */
    public function getStringValue()
    {
        return $this->__toString();
    }

    /**
     * @param Integer $number
     * @return Integer
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
     * @param Integer $number
     * @return Integer
     */
    public function subtract(Integer $number)
    {
        return $this;
    }

    /**
     * @param Integer $number
     * @return Integer
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

            $subResultWithZeros = $subResult->getStringValue() . str_repeat('0', $delta);

            $result = $result->add(
                Integer::createByString($subResultWithZeros)
            );
        }

        return $result;
    }

    /**
     * @param int $number
     * @return Integer
     */
    public function multiplyByInt(int $number)
    {
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

        return Integer::createByString($stringHolder);
    }
}
