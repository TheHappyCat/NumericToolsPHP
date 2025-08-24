<?php namespace TheHappyCat\NumericTools;

use Exception;

use TheHappyCat\NumericTools\Operators\Addition\AdditionInterface;
use TheHappyCat\NumericTools\Operators\Addition\IntegerAddition;

use TheHappyCat\NumericTools\Utils\NumericStringUtils;

/**
 * Class Integer
 * @package TheHappyCat\NumericTools
 */
class Integer extends Number
{
    /**
     * @var array
     */
    public $value = [];

    /**
     * @var bool
     */
    private $negative = false;

    /**
     * @var AdditionInterface
     */
    private $additionHandler;

    /**
     * @param AdditionInterface $additionHandler
     */
    public function setAdditionHandler($additionHandler) {
        $this->additionHandler = $additionHandler;
    }

    /**
     * @param int $value
     * @return \TheHappyCat\NumericTools\Integer
     */
    public static function createByInt(int $value)
    {
        $instance = new self();
        $instance->setIntValue($value);
        $instance->setAdditionHandler(new IntegerAddition());
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
        $instance->setAdditionHandler(new IntegerAddition());
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
     * @param \TheHappyCat\NumericTools\Integer $number
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function add(Integer $number)
    {
        if ($this->additionHandler === null) {
            throw new Exception("AdditionInterface handler not setup");
        }

        return $this->additionHandler->add($this, $number);
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
            $negative ? '-' . NumericStringUtils::purgeZeros($stringHolder) : NumericStringUtils::purgeZeros($stringHolder)
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

            $subResultWithZeros = NumericStringUtils::purgeZeros($subResult->getStringValue() . str_repeat('0', $delta));

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
            NumericStringUtils::purgeZeros($stringHolder)
        );
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
     * @param \TheHappyCat\NumericTools\Integer $divisor
     * @param bool $modMode
     * @return \TheHappyCat\NumericTools\Integer
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

        // If divisor is greater than dividend, result is 0
        if ($divisor->greaterThan($this)) {
            if ($modMode) {
                return $this; // Remainder is the dividend itself
            }
            return Integer::createByInt(0); // Quotient is 0
        }

        // Optimized long division algorithm
        $dividend = $this->getStringValue();
        $divisorStr = $divisor->getStringValue();
        
        $quotient = '';
        $remainder = '';
        $dividendLength = strlen($dividend);
        
        for ($i = 0; $i < $dividendLength; $i++) {
            $remainder .= $dividend[$i];
            $remainder = ltrim($remainder, '0');
            if (empty($remainder)) $remainder = '0';
            
            // Find how many times divisor goes into current remainder
            $count = 0;
            $tempDivisor = Integer::createByString($divisorStr);
            $tempRemainder = Integer::createByString($remainder);
            
            while ($tempRemainder->greaterOrEqualTo($tempDivisor)) {
                $tempRemainder = $tempRemainder->subtract($tempDivisor);
                $count++;
            }
            
            $quotient .= $count;
            $remainder = $tempRemainder->getStringValue();
        }
        
        $quotient = ltrim($quotient, '0');
        if (empty($quotient)) $quotient = '0';
        
        if ($modMode) {
            return Integer::createByString($remainder);
        }
        
        return Integer::createByString($quotient);
    }

    /**
     * Fast modular exponentiation (a^b mod m)
     * Essential for primality testing
     * 
     * @param \TheHappyCat\NumericTools\Integer $exponent
     * @param \TheHappyCat\NumericTools\Integer $modulus
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function modPow(Integer $exponent, Integer $modulus)
    {
        if ($modulus->isZero()) {
            throw new Exception("Modulus cannot be zero");
        }

        $result = Integer::createByInt(1);
        $base = $this->mod($modulus);
        $exp = $exponent;

        while (!$exp->isZero()) {
            // If exponent is odd, multiply result with base
            if ($exp->mod(Integer::createByInt(2))->getStringValue() === '1') {
                $result = $result->multiplyBy($base)->mod($modulus);
            }
            
            // Square the base
            $base = $base->multiplyBy($base)->mod($modulus);
            
            // Divide exponent by 2
            $exp = $exp->divideBy(Integer::createByInt(2));
        }

        return $result;
    }

    /**
     * Greatest Common Divisor using Euclidean algorithm
     * 
     * @param \TheHappyCat\NumericTools\Integer $other
     * @return \TheHappyCat\NumericTools\Integer
     */
    public function gcd(Integer $other)
    {
        $a = $this;
        $b = $other;

        while (!$b->isZero()) {
            $temp = $b;
            $b = $a->mod($b);
            $a = $temp;
        }

        return $a;
    }

    /**
     * Least Common Multiple
     * 
     * @param \TheHappyCat\NumericTools\Integer $other
     * @return \TheHappyCat\NumericTools\Integer
     */
    public function lcm(Integer $other)
    {
        if ($this->isZero() || $other->isZero()) {
            return Integer::createByInt(0);
        }

        $gcd = $this->gcd($other);
        $absProduct = $this->multiplyBy($other);
        
        return $absProduct->divideBy($gcd);
    }

    /**
     * Miller-Rabin probabilistic primality test
     * Fast primality testing for large numbers
     * 
     * @param int $iterations Number of test iterations (default: 5)
     * @return bool
     */
    public function isProbablePrime(int $iterations = 5): bool
    {
        if ($this->isZero() || $this->getStringValue() === '1') {
            return false;
        }
        
        if ($this->getStringValue() === '2' || $this->getStringValue() === '3') {
            return true;
        }

        // Check if even
        if ($this->mod(Integer::createByInt(2))->isZero()) {
            return false;
        }

        // Write n-1 as 2^r * d
        $nMinusOne = $this->subtract(Integer::createByInt(1));
        $r = 0;
        $d = $nMinusOne;
        
        while ($d->mod(Integer::createByInt(2))->isZero()) {
            $r++;
            $d = $d->divideBy(Integer::createByInt(2));
        }

        // Test with small bases
        $bases = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37];
        
        for ($i = 0; $i < $iterations && $i < count($bases); $i++) {
            $base = Integer::createByInt($bases[$i]);
            
            if ($base->greaterOrEqualTo($this)) {
                continue;
            }
            
            if (!$this->millerRabinTest($base, $r, $d)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Helper method for Miller-Rabin test
     * 
     * @param \TheHappyCat\NumericTools\Integer $base
     * @param int $r
     * @param \TheHappyCat\NumericTools\Integer $d
     * @return bool
     */
    private function millerRabinTest(Integer $base, int $r, Integer $d): bool
    {
        $x = $base->modPow($d, $this);
        
        if ($x->getStringValue() === '1' || $x->getStringValue() === $this->subtract(Integer::createByInt(1))->getStringValue()) {
            return true;
        }
        
        for ($i = 1; $i < $r; $i++) {
            $x = $x->multiplyBy($x)->mod($this);
            
            if ($x->getStringValue() === $this->subtract(Integer::createByInt(1))->getStringValue()) {
                return true;
            }
            
            if ($x->getStringValue() === '1') {
                return false;
            }
        }
        
        return false;
    }

    /**
     * Check if number is prime (deterministic for small numbers, probabilistic for large)
     * 
     * @return bool
     */
    public function isPrime(): bool
    {
        // Negative numbers are not prime
        if ($this->isNegative()) {
            return false;
        }
        
        // For numbers < 2^64, use deterministic test
        if ($this->getLength() <= 20) {
            return $this->isProbablePrime(10);
        }
        
        // For larger numbers, use probabilistic test
        return $this->isProbablePrime(20);
    }

    /**
     * Brute force primality test - tests every possible divisor
     * WARNING: Extremely slow for large numbers!
     * 
     * @return bool
     */
    public function isPrimeBruteForce(): bool
    {
        if ($this->isNegative() || $this->isZero() || $this->getStringValue() === '1') {
            return false;
        }
        
        if ($this->getStringValue() === '2' || $this->getStringValue() === '3') {
            return true;
        }
        
        // Check if even
        if ($this->mod(Integer::createByInt(2))->isZero()) {
            return false;
        }
        
        $sqrt = $this->sqrt();
        $divisor = Integer::createByInt(3);
        
        // Test odd divisors up to √n
        while ($divisor->lessThanOrEqualTo($sqrt)) {
            if ($this->mod($divisor)->isZero()) {
                return false; // Found a divisor
            }
            $divisor = $divisor->add(Integer::createByInt(2));
        }
        
        return true; // No divisors found
    }

    /**
     * Check if this number is less than or equal to another number
     * 
     * @param Integer $number
     * @return bool
     */
    public function lessThanOrEqualTo(Integer $number): bool
    {
        return !$this->greaterThan($number);
    }

    /**
     * Check if this number equals another number
     * 
     * @param Integer $number
     * @return bool
     */
    public function equals(Integer $number): bool
    {
        return $this->getStringValue() === $number->getStringValue();
    }

    /**
     * Calculate the square root of this number (integer part only)
     * 
     * @return Integer
     */
    public function sqrt(): Integer
    {
        if ($this->isNegative()) {
            throw new Exception("Cannot calculate square root of negative number");
        }
        
        if ($this->isZero() || $this->getStringValue() === '1') {
            return $this;
        }
        
        // Use binary search to find square root
        $left = Integer::createByInt(1);
        $right = $this;
        $result = Integer::createByInt(1);
        
        while ($left->lessThanOrEqualTo($right)) {
            $mid = $left->add($right)->divideBy(Integer::createByInt(2));
            $square = $mid->multiplyBy($mid);
            
            if ($square->lessThanOrEqualTo($this)) {
                $result = $mid;
                $left = $mid->add(Integer::createByInt(1));
            } else {
                $right = $mid->subtract(Integer::createByInt(1));
            }
        }
        
        return $result;
    }

    /**
     * Check if this number is a power of 2
     * 
     * @return bool
     */
    public function isPowerOfTwo(): bool
    {
        if ($this->isZero() || $this->isNegative()) {
            return false;
        }
        
        $n = $this;
        while (!$n->isZero() && !$n->equals(Integer::createByInt(1))) {
            if (!$n->mod(Integer::createByInt(2))->isZero()) {
                return false;
            }
            $n = $n->divideBy(Integer::createByInt(2));
        }
        
        return true;
    }

    /**
     * Get the power of 2 if this number is a power of 2
     * 
     * @return Integer|null
     */
    public function getPowerOfTwo(): ?Integer
    {
        if (!$this->isPowerOfTwo()) {
            return null;
        }
        
        $power = Integer::createByInt(0);
        $n = $this;
        
        while (!$n->equals(Integer::createByInt(1))) {
            $power = $power->add(Integer::createByInt(1));
            $n = $n->divideBy(Integer::createByInt(2));
        }
        
        return $power;
    }
}
