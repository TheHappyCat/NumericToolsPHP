<?php

require_once __DIR__ . '/../src/thehappycat/numerictools/Integer.php';
require_once __DIR__ . '/../src/thehappycat/numerictools/NumberValidations.php';

use PHPUnit\Framework\TestCase;
use TheHappyCat\NumericTools\Integer;
use TheHappyCat\NumericTools\NumberValidations;

/**
 * Class IntegerTest
 */
class IntegerTest extends TestCase
{
    public function testIntegerValidations()
    {
        $this->assertNotTrue(NumberValidations::stringIsInteger(''));

        $this->assertNotTrue(NumberValidations::stringIsInteger('-0123'));

        $this->assertNotTrue(NumberValidations::stringIsInteger('-0'));

        $this->assertNotTrue(NumberValidations::stringIsInteger('-00'));

        $this->assertNotTrue(NumberValidations::stringIsInteger('00'));

        $this->assertNotTrue(NumberValidations::stringIsInteger('0123'));

        $this->assertTrue(NumberValidations::stringIsInteger('-2048'));

        $this->assertTrue(NumberValidations::stringIsInteger('0'));

        $this->assertTrue(NumberValidations::stringIsInteger('1'));

        $this->assertTrue(NumberValidations::stringIsInteger('1980'));
        
        $this->assertTrue(NumberValidations::stringIsInteger('123456789876543212345678987654321'));
        
        $this->assertTrue(NumberValidations::stringIsInteger('12345678987654321'));
    }

    public function testCreateDefault()
    {
        $number = new Integer();
        $this->assertNotNull($number);
    }

    public function testCreateByInt()
    {
        $number = Integer::createByInt(123);
        $this->assertNotNull($number);
    }

    public function testCreateByString()
    {
        $number = Integer::createByString('-274');
        $this->assertNotNull($number);

        $number = Integer::createByString('0');
        $this->assertNotNull($number);

        $number = Integer::createByString('123456789');
        $this->assertNotNull($number);
    }

    public function testPrint()
    {
        /*
         * Test creating by int with negative, zero and positive integers.
         */

        $number = Integer::createByInt(-274);
        $this->assertEquals('-274', strval($number));

        $number = Integer::createByInt(0);
        $this->assertEquals('0', strval($number));

        $number = Integer::createByInt(123);
        $this->assertEquals('123', strval($number));

        /*
         * Test creating by string with negative, zero and positive integers.
         */

        $number = Integer::createByString('-274');
        $this->assertEquals('-274', strval($number));

        $number = Integer::createByString('0');
        $this->assertEquals('0', strval($number));

        $number = Integer::createByString('123');
        $this->assertEquals('123', strval($number));
    }

    public function testAdd()
    {
        $baseValue = 9999;

        $a = Integer::createByInt($baseValue);

        for ($i = 0; $i < $baseValue; $i++) {
            $numericAddition = $baseValue + $i;
            $b = $a->add(Integer::createByInt($i));

            $this->assertEquals('' . $numericAddition, strval($b));
        }
    }
    
    public function testLargeAddition()
    {
        $a = Integer::createByString('1234567898765432123456789876543212345678987654321');
        $b = Integer::createByString('987654321234567898765432123456789');
        $c = $a->add($b);
        
        $expectedResult = '1234567898765433111111111111111111111111111111110';
        
        $this->assertEquals($expectedResult, strval($c));
    }

    public function testSubtract()
    {
        $baseValue = 9999;

        $a = Integer::createByInt($baseValue);

        for ($i = 0; $i <= $baseValue; $i++) {
            $this->assertEquals('' . $baseValue - $i, strval($a->subtract(Integer::createByInt($i))));
        }
    }
    
    public function testLargeSubtraction()
    {
        $a = Integer::createByString('1234567898765432123456789876543212345678987654321');
        $b = Integer::createByString('987654321234567898765432123456789');
        $c = $a->subtract($b);
        
        $expectedResult = '1234567898765431135802468641975313580246864197532';
        
        $this->assertEquals($expectedResult, strval($c));
    }
    
    public function testNegativeSubtraction()
    {
        $a = Integer::createByString('1234567898765432123456789876543212345678987654321');
        $b = Integer::createByString('-987654321234567898765432123456789');
        $c = $a->subtract($b);
        
        $expectedResult = '1234567898765433111111111111111111111111111111110';
        
        $this->assertEquals($expectedResult, strval($c));
    }

    public function testSubtractWithNegativeResults()
    {
        $baseValue = 9999;

        $a = Integer::createByInt($baseValue);

        for ($i=0; $i<=$baseValue * 2; $i++) {
            $this->assertEquals('' . $baseValue - $i, strval($a->subtract(Integer::createByInt($i))));
        }
    }
    
    public function testLargeNegativeSubtraction()
    {
        $a = Integer::createByString('987654321234567898765432123456789');
        $b = Integer::createByString('1234567898765432123456789876543212345678987654321');
        $c = $a->subtract($b);
        
        $expectedResult = '-1234567898765431135802468641975313580246864197532';
        
        $this->assertEquals($expectedResult, strval($c));
    }

    public function testMultiplyByInt()
    {
        $number = Integer::createByString('123');

        $result = $number->multiplyByInt(4);

        $this->assertEquals('492', strval($result));
    }
    
    public function testMultiplyByIntException()
    {
        $number = Integer::createByString('123');
        
        $this->expectException(Exception::class);
        
        $result = $number->multiplyByInt(10);
    }

    public function testMultiply()
    {
        $baseValue = 9999;

        $a = Integer::createByInt($baseValue);

        for ($i = 0; $i <= $baseValue; $i++) {
            $this->assertEquals('' . ($baseValue * $i), strval($a->multiplyBy(Integer::createByInt($i))));
        }

        $a = Integer::createByString('1');
        $b = Integer::createByString('1');
        $c = $a->multiplyBy($b);
        $this->assertEquals('1', strval($c));

        $a = Integer::createByString('123456789');
        $b = Integer::createByString('999999');
        $c = $a->multiplyBy($b);
        $this->assertEquals('123456665543211', strval($c));

        $a = Integer::createByString('999999999999');
        $b = Integer::createByString('789');
        $c = $a->multiplyBy($b);
        $this->assertEquals('788999999999211', strval($c));

        $a = Integer::createByString('2');
        $b = Integer::createByString('2');
        $c = $a->multiplyBy($b)->multiplyBy($b)->multiplyBy($b)->multiplyBy($b)->multiplyBy($b)->multiplyBy($b)->multiplyBy($b);
        $this->assertEquals('256', strval($c));

        $a = Integer::createByString('1234567898765432123456789876543212345678987654321');
        $b = Integer::createByString('987654321234567898765432123456789');
        $c = $a->multiplyBy($b);
        $this->assertEquals('1219326320073159600060966114921506736777910409998442005792202408166072245112635269', strval($c));
    }

    public function testGreaterThan()
    {
        // false
        $a = Integer::createByString('1');
        $b = Integer::createByString('1');
        $this->assertFalse($a->greaterThan($b));

        // true
        $a = Integer::createByString('2');
        $b = Integer::createByString('1');
        $this->assertTrue($a->greaterThan($b));

        // false
        $a = Integer::createByString('1');
        $b = Integer::createByString('2');
        $this->assertFalse($a->greaterThan($b));

        // false
        $a = Integer::createByString('5432');
        $b = Integer::createByString('5678');
        $this->assertFalse($a->greaterThan($b));

        // false
        $a = Integer::createByString('5543');
        $b = Integer::createByString('5567');
        $this->assertFalse($a->greaterThan($b));

        // false
        $a = Integer::createByString('5554');
        $b = Integer::createByString('5556');
        $this->assertFalse($a->greaterThan($b));

        // false (first case)
        $a = Integer::createByString('5555');
        $b = Integer::createByString('5555');
        $this->assertFalse($a->greaterThan($b));

        // true
        $a = Integer::createByString('5556');
        $b = Integer::createByString('5554');
        $this->assertTrue($a->greaterThan($b));

        $baseValue = 9999;

        $a = Integer::createByInt($baseValue);

        for ($i = 0; $i < $baseValue; $i++) {
            $b = Integer::createByInt($i);

            $this->assertTrue($a->greaterThan($b));
        }

        // true
        $a = Integer::createByInt(1500);
        $b = Integer::createByInt(1492);
        $this->assertTrue($a->greaterThan($b));
    }

    public function testStringLength()
    {
        $stringLength = 1000000;

        $stringHolder = '';

        for($i = 0; $i < $stringLength; $i++) {
            $stringHolder = $stringHolder . rand(0, 9);
        }

        $this->assertEquals($stringLength, strlen($stringHolder));
    }

    public function testMaximumMultiplier()
    {
        $dividend = Integer::createByInt(1);
        $divisor = Integer::createByInt(1);
        $multiplier = $dividend->getMaximumMultiplier($divisor);
        $this->assertEquals('1', strval($multiplier));

        $dividend = Integer::createByInt(100);
        $divisor = Integer::createByInt(2);
        $multiplier = $dividend->getMaximumMultiplier($divisor);
        $this->assertEquals('50', strval($multiplier));

        $dividend = Integer::createByInt(59580);
        $divisor = Integer::createByInt(30);
        $multiplier = $dividend->getMaximumMultiplier($divisor);
        $this->assertEquals('1986', strval($multiplier));

        $dividend = Integer::createByInt(1580);
        $divisor = Integer::createByInt(30);
        $multiplier = $dividend->getMaximumMultiplier($divisor);
        $this->assertEquals('52', $multiplier);

        $dividend = Integer::createByInt(2474);
        $divisor = Integer::createByInt(987);
        $multiplier = $dividend->getMaximumMultiplier($divisor);
        $this->assertEquals('2', $multiplier);
    }
    
    public function testMaxMultiplierException()
    {
        $dividend = Integer::createByInt(10);
        $divisor = Integer::createByInt(40);
        
        $this->expectException(Exception::class);

        $multiplier = $dividend->getMaximumMultiplier($divisor);
    }

    public function testNumberLength()
    {
        $number = Integer::createByInt(1234);
        $this->assertEquals($number->getLength(), 4);
    }

    public function testDivision()
    {
        /**
         * 0 / 1
         */

        $dividend = Integer::createByString('0');
        $divisor = Integer::createByString('1');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('0', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('0', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 1 / 1
         */

        $dividend = Integer::createByString('1');
        $divisor = Integer::createByString('1');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('1', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('0', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 25 / 5
         */

        $dividend = Integer::createByString('25');
        $divisor = Integer::createByString('5');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('5', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('0', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 26 / 5
         */

        $dividend = Integer::createByString('26');
        $divisor = Integer::createByString('5');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('5', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('1', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 30 / 3
         */

        $dividend = Integer::createByString('30');
        $divisor = Integer::createByString('3');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('10', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('0', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 1234567890 / 987
         * http://www.wolframalpha.com/input/?i=1234567890+%2F+987
         */

        $dividend = Integer::createByString('1234567890');
        $divisor = Integer::createByString('987');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('1250828', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('654', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 98765432123456789 / 123456
         * http://www.wolframalpha.com/input/?i=98765432123456789+%2F+123456
         */

        $dividend = Integer::createByString('98765432123456789');
        $divisor = Integer::createByString('123456');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('800005120232', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('94997', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);

        /**
         * 987654321234567898765432123456789 / 12345678987654321
         * http://www.wolframalpha.com/input/?i=987654321234567898765432123456789+%2F+12345678987654321
         */

        $dividend = Integer::createByString('987654321234567898765432123456789');
        $divisor = Integer::createByString('12345678987654321');
        $quotient = $dividend->divideBy($divisor);
        $this->assertEquals('80000000180000000', $quotient);

        $mod = $dividend->mod($divisor);
        $this->assertEquals('987654343456789', $mod);

        $originalNumber = $quotient->multiplyBy($divisor)->add($mod);
        $this->assertEquals($dividend, $originalNumber);
    }

    public function testDivisionByZeroException()
    {
        $dividend = Integer::createByString('0');
        $divisor = Integer::createByString('0');

        $this->expectException(Exception::class);

        $dividend->divideBy($divisor);
    }
    
    public function testDivisionByBiggerDivisorException()
    {
        $dividend = Integer::createByString('10');
        $divisor = Integer::createByString('20');

        $this->expectException(Exception::class);

        $dividend->divideBy($divisor);
    }
    
    public function testSeveralDivisions()
    {
        $initialIndex = 2;

        for ($i = $initialIndex; $i < $initialIndex + 1000; $i++) {
            $numericDividend = $i;
            $numericDivisor = 2;
            $numericQuotient = intdiv($numericDividend, $numericDivisor);
            $numericModule = $numericDividend % $numericDivisor;

            $objectDividend = Integer::createByInt($i);
            $objectDivisor = Integer::createByInt(2);
            $objectQuotient= $objectDividend->divideBy($objectDivisor);
            $objectModule = $objectDividend->mod($objectDivisor);

            $this->assertEquals($numericQuotient . '', $objectQuotient);
            $this->assertEquals($numericModule . '', $objectModule);
        }
    }

    public function testCreatingLargeNumber()
    {
        $largeNumber = Integer::createByString('12345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321');
        $this->assertTrue(NumberValidations::stringIsInteger($largeNumber));

        $addition = $largeNumber->add(Integer::createByInt(1234));
        $this->assertTrue(NumberValidations::stringIsInteger($addition));
    }
}
