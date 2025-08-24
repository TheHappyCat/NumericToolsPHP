<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TheHappyCat\NumericTools\Integer;

/**
 * Class PrimeNumberTest
 * Tests for prime number functionality
 */
class PrimeNumberTest extends TestCase
{
    public function testIsPrime()
    {
        // Test small prime numbers
        $this->assertTrue(Integer::createByInt(2)->isPrime());
        $this->assertTrue(Integer::createByInt(3)->isPrime());
        $this->assertTrue(Integer::createByInt(5)->isPrime());
        $this->assertTrue(Integer::createByInt(7)->isPrime());
        $this->assertTrue(Integer::createByInt(11)->isPrime());
        $this->assertTrue(Integer::createByInt(13)->isPrime());
        $this->assertTrue(Integer::createByInt(17)->isPrime());
        $this->assertTrue(Integer::createByInt(19)->isPrime());
        $this->assertTrue(Integer::createByInt(23)->isPrime());
        $this->assertTrue(Integer::createByInt(29)->isPrime());
        $this->assertTrue(Integer::createByInt(31)->isPrime());
        $this->assertTrue(Integer::createByInt(37)->isPrime());
        
        // Test small composite numbers
        $this->assertFalse(Integer::createByInt(1)->isPrime());
        $this->assertFalse(Integer::createByInt(4)->isPrime());
        $this->assertFalse(Integer::createByInt(6)->isPrime());
        $this->assertFalse(Integer::createByInt(8)->isPrime());
        $this->assertFalse(Integer::createByInt(9)->isPrime());
        $this->assertFalse(Integer::createByInt(10)->isPrime());
        $this->assertFalse(Integer::createByInt(12)->isPrime());
        $this->assertFalse(Integer::createByInt(14)->isPrime());
        $this->assertFalse(Integer::createByInt(15)->isPrime());
        $this->assertFalse(Integer::createByInt(16)->isPrime());
        $this->assertFalse(Integer::createByInt(18)->isPrime());
        $this->assertFalse(Integer::createByInt(20)->isPrime());
        $this->assertFalse(Integer::createByInt(21)->isPrime());
        $this->assertFalse(Integer::createByInt(22)->isPrime());
        $this->assertFalse(Integer::createByInt(24)->isPrime());
        $this->assertFalse(Integer::createByInt(25)->isPrime());
        $this->assertFalse(Integer::createByInt(26)->isPrime());
        $this->assertFalse(Integer::createByInt(27)->isPrime());
        $this->assertFalse(Integer::createByInt(28)->isPrime());
        $this->assertFalse(Integer::createByInt(30)->isPrime());
        $this->assertFalse(Integer::createByInt(32)->isPrime());
        $this->assertFalse(Integer::createByInt(33)->isPrime());
        $this->assertFalse(Integer::createByInt(34)->isPrime());
        $this->assertFalse(Integer::createByInt(35)->isPrime());
        $this->assertFalse(Integer::createByInt(36)->isPrime());
        $this->assertFalse(Integer::createByInt(38)->isPrime());
        $this->assertFalse(Integer::createByInt(39)->isPrime());
        $this->assertFalse(Integer::createByInt(40)->isPrime());
    }

    public function testIsProbablePrime()
    {
        // Test with different iteration counts
        $this->assertTrue(Integer::createByInt(2)->isProbablePrime(1));
        $this->assertTrue(Integer::createByInt(2)->isProbablePrime(5));
        $this->assertTrue(Integer::createByInt(2)->isProbablePrime(10));
        
        $this->assertFalse(Integer::createByInt(4)->isProbablePrime(1));
        $this->assertFalse(Integer::createByInt(4)->isProbablePrime(5));
        $this->assertFalse(Integer::createByInt(4)->isProbablePrime(10));
    }

    public function testModPow()
    {
        // Test basic modular exponentiation
        $base = Integer::createByInt(2);
        $exponent = Integer::createByInt(3);
        $modulus = Integer::createByInt(5);
        
        $result = $base->modPow($exponent, $modulus);
        $this->assertEquals('3', $result->getStringValue()); // 2^3 mod 5 = 8 mod 5 = 3
        
        // Test with larger numbers
        $base = Integer::createByInt(3);
        $exponent = Integer::createByInt(4);
        $modulus = Integer::createByInt(7);
        
        $result = $base->modPow($exponent, $modulus);
        $this->assertEquals('4', $result->getStringValue()); // 3^4 mod 7 = 81 mod 7 = 4
    }

    public function testGcd()
    {
        // Test basic GCD calculations
        $this->assertEquals('6', Integer::createByInt(48)->gcd(Integer::createByInt(18))->getStringValue());
        $this->assertEquals('1', Integer::createByInt(17)->gcd(Integer::createByInt(13))->getStringValue());
        $this->assertEquals('5', Integer::createByInt(25)->gcd(Integer::createByInt(15))->getStringValue());
        $this->assertEquals('12', Integer::createByInt(60)->gcd(Integer::createByInt(48))->getStringValue());
    }

    public function testLcm()
    {
        // Test basic LCM calculations
        $this->assertEquals('144', Integer::createByInt(48)->lcm(Integer::createByInt(18))->getStringValue());
        $this->assertEquals('221', Integer::createByInt(17)->lcm(Integer::createByInt(13))->getStringValue());
        $this->assertEquals('75', Integer::createByInt(25)->lcm(Integer::createByInt(15))->getStringValue());
        $this->assertEquals('240', Integer::createByInt(60)->lcm(Integer::createByInt(48))->getStringValue());
    }

    public function testLargePrimeNumbers()
    {
        // Test some known larger prime numbers
        $largePrime = Integer::createByString('1000000007'); // Known prime
        $this->assertTrue($largePrime->isProbablePrime(10));
        
        $largeComposite = Integer::createByString('1000000008'); // Known composite
        $this->assertFalse($largeComposite->isProbablePrime(10));
    }

    public function testEdgeCases()
    {
        // Test edge cases
        $this->assertFalse(Integer::createByInt(0)->isPrime());
        $this->assertFalse(Integer::createByInt(1)->isPrime());
        $this->assertTrue(Integer::createByInt(2)->isPrime());
        
        // Test negative numbers (should handle gracefully)
        $this->assertFalse(Integer::createByString('-5')->isPrime());
    }
}
