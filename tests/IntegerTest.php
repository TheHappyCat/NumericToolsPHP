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

        $this->assertNotTrue(NumberValidations::stringIsInteger('0123'));

        $this->assertTrue(NumberValidations::stringIsInteger('-2048'));

        $this->assertTrue(NumberValidations::stringIsInteger('0'));

        $this->assertTrue(NumberValidations::stringIsInteger('1'));

        $this->assertTrue(NumberValidations::stringIsInteger('1980'));
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

    public function testMultiplyByInt()
    {
        $number = Integer::createByString('123');

        $result = $number->multiplyByInt(4);

        $stringResult = strval($result);

        $this->assertEquals('492', $stringResult);
    }

    public function testAdd()
    {
        $a = Integer::createByString('6150');
        $b = Integer::createByString('738');
        $c = $a->add($b);
        $this->assertEquals('6888', strval($c));

        $a = Integer::createByString('738');
        $b = Integer::createByString('6150');
        $c = $a->add($b);
        $this->assertEquals('6888', strval($c));

        $a = Integer::createByString('1234');
        $b = Integer::createByString('0');
        $c = $a->add($b);
        $this->assertEquals('1234', strval($c));

        $a = Integer::createByString('0');
        $b = Integer::createByString('1234');
        $c = $a->add($b);
        $this->assertEquals('1234', strval($c));

        $a = Integer::createByString('1234');
        $b = Integer::createByString('1');
        $c = $a->add($b);
        $this->assertEquals('1235', strval($c));

        $a = Integer::createByString('1');
        $b = Integer::createByString('1234');
        $c = $a->add($b);
        $this->assertEquals('1235', strval($c));
    }

    public function testMultiply()
    {
        $a = Integer::createByString('123');
        $b = Integer::createByString('456');
        $c = $a->multiplyBy($b);
        $this->assertEquals('56088', strval($c));

        $a = Integer::createByString('123');
        $b = Integer::createByString('987');
        $c = $a->multiplyBy($b);
        $this->assertEquals('121401', strval($c));

        $a = Integer::createByString('123');
        $b = Integer::createByString('45');
        $c = $a->multiplyBy($b);
        $this->assertEquals('5535', strval($c));

        $a = Integer::createByString('45');
        $b = Integer::createByString('123');
        $c = $a->multiplyBy($b);
        $this->assertEquals('5535', strval($c));

        $a = Integer::createByString('123456789');
        $b = Integer::createByString('123');
        $c = $a->multiplyBy($b);
        $this->assertEquals('15185185047', strval($c));

        $a = Integer::createByString('123456789');
        $b = Integer::createByString('1');
        $c = $a->multiplyBy($b);
        $this->assertEquals('123456789', strval($c));

        $a = Integer::createByString('999');
        $b = Integer::createByString('9');
        $c = $a->multiplyBy($b);
        $this->assertEquals('8991', strval($c));

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
    }

    public function testGreaterThan()
    {
        $a = Integer::createByString('1');
        $b = Integer::createByString('123');
        $this->assertFalse($a->greaterThan($b));

        $a = Integer::createByString('123');
        $b = Integer::createByString('1');
        $this->assertTrue($a->greaterThan($b));

        $a = Integer::createByString('123');
        $b = Integer::createByString('123');
        $this->assertFalse($a->greaterThan($b));

        $a = Integer::createByString('123');
        $b = Integer::createByString('456');
        $this->assertFalse($a->greaterThan($b));

        $a = Integer::createByString('456');
        $b = Integer::createByString('123');
        $this->assertTrue($a->greaterThan($b));
    }
}
