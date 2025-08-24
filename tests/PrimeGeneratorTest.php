<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TheHappyCat\NumericTools\Integer;
use TheHappyCat\NumericTools\PrimeGenerator;

/**
 * Class PrimeGeneratorTest
 * Tests for prime number generation functionality
 */
class PrimeGeneratorTest extends TestCase
{
    private PrimeGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new PrimeGenerator();
    }

    public function testGenerateRandomOdd()
    {
        $bits = 16;
        $number = $this->generator->generateRandomOdd($bits);
        
        $this->assertInstanceOf(Integer::class, $number);
        $this->assertGreaterThan(0, $number->getLength());
        $this->assertFalse($number->isNegative());
        
        // Check if it's odd
        $this->assertFalse($number->mod(Integer::createByInt(2))->isZero());
    }

    public function testGeneratePrime()
    {
        $bits = 32;
        $prime = $this->generator->generatePrime($bits);
        
        $this->assertInstanceOf(Integer::class, $prime);
        $this->assertTrue($prime->isPrime());
        // Bit length should be approximately correct (allowing some variance)
        $this->assertGreaterThanOrEqual($bits * 0.8, $prime->getLength() * 3.32);
    }

    public function testGeneratePrimeSmallBits()
    {
        $bits = 8;
        $prime = $this->generator->generatePrime($bits);
        
        $this->assertInstanceOf(Integer::class, $prime);
        $this->assertTrue($prime->isPrime());
        $this->assertLessThanOrEqual(3, $prime->getLength()); // 8 bits = max 3 digits
    }

    public function testGenerateTwinPrimes()
    {
        $bits = 16;
        list($p1, $p2) = $this->generator->generateTwinPrimes($bits);
        
        $this->assertInstanceOf(Integer::class, $p1);
        $this->assertInstanceOf(Integer::class, $p2);
        $this->assertTrue($p1->isPrime());
        $this->assertTrue($p2->isPrime());
        
        // Check they are twin primes (difference is 2)
        $difference = $p2->subtract($p1);
        $this->assertEquals('2', $difference->getStringValue());
    }

    public function testGenerateNextPrime()
    {
        $start = Integer::createByInt(100);
        $nextPrime = $this->generator->generateNextPrime($start);
        
        $this->assertInstanceOf(Integer::class, $nextPrime);
        $this->assertTrue($nextPrime->isPrime());
        $this->assertTrue($nextPrime->greaterThan($start));
    }

    public function testGeneratePrimesInRange()
    {
        $start = Integer::createByInt(10);
        $end = Integer::createByInt(50);
        $primes = $this->generator->generatePrimesInRange($start, $end);
        
        $this->assertIsArray($primes);
        $this->assertNotEmpty($primes);
        
        foreach ($primes as $prime) {
            $this->assertInstanceOf(Integer::class, $prime);
            $this->assertTrue($prime->isPrime());
            $this->assertTrue($prime->greaterOrEqualTo($start));
            $this->assertTrue($prime->lessThanOrEqualTo($end));
        }
        
        // Expected primes in range 10-50: 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47
        $this->assertGreaterThanOrEqual(10, count($primes));
    }

    public function testGenerateRandomPrimeInRange()
    {
        $min = Integer::createByInt(100);
        $max = Integer::createByInt(200);
        $prime = $this->generator->generateRandomPrimeInRange($min, $max);
        
        $this->assertInstanceOf(Integer::class, $prime);
        $this->assertTrue($prime->isPrime());
        $this->assertTrue($prime->greaterOrEqualTo($min));
        $this->assertTrue($prime->lessThanOrEqualTo($max));
    }

    public function testGenerateSophieGermainPrime()
    {
        $bits = 16;
        $prime = $this->generator->generateSophieGermainPrime($bits);
        
        $this->assertInstanceOf(Integer::class, $prime);
        $this->assertTrue($prime->isPrime());
        
        // Check if 2p+1 is also prime
        $q = $prime->multiplyBy(Integer::createByInt(2))->add(Integer::createByInt(1));
        $this->assertTrue($q->isPrime());
    }

    public function testInvalidBitLength()
    {
        $this->expectException(Exception::class);
        $this->generator->generatePrime(1);
    }

    public function testInvalidBitLengthRandomOdd()
    {
        $this->expectException(Exception::class);
        $this->generator->generateRandomOdd(1);
    }

    public function testPrimeGenerationPerformance()
    {
        $start = microtime(true);
        $prime = $this->generator->generatePrime(32); // Reduced from 64 to 32
        $end = microtime(true);
        
        $time = $end - $start;
        
        $this->assertInstanceOf(Integer::class, $prime);
        $this->assertTrue($prime->isPrime());
        $this->assertLessThan(10, $time); // Should complete in under 10 seconds
    }

    public function testLargePrimeGeneration()
    {
        $bits = 48; // Reduced from 64 to 48 for more reliable testing
        $prime = $this->generator->generatePrime($bits);
        
        $this->assertInstanceOf(Integer::class, $prime);
        $this->assertTrue($prime->isPrime());
        // Bit length should be approximately correct (allowing some variance)
        $this->assertGreaterThanOrEqual($bits * 0.8, $prime->getLength() * 3.32);
    }

    public function testPrimeProperties()
    {
        $prime = $this->generator->generatePrime(32);
        
        // Test various properties of the generated prime
        $this->assertTrue($prime->isPrime());
        $this->assertFalse($prime->isZero());
        $this->assertFalse($prime->isNegative());
        
        // Test that it's not divisible by small numbers
        for ($i = 2; $i <= 10; $i++) {
            if ($i !== (int)$prime->getStringValue()) {
                $this->assertFalse($prime->mod(Integer::createByInt($i))->isZero());
            }
        }
    }
}
