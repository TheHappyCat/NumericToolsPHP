<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TheHappyCat\NumericTools\Integer;

/**
 * Class PrimalityComparisonTest
 * Compares performance of probabilistic vs brute force primality testing
 */
class PrimalityComparisonTest extends TestCase
{
    public function testSmallNumberComparison()
    {
        $number = Integer::createByString('97'); // Known prime
        
        echo "\n=== Testing Small Number (97) ===\n";
        
        // Test probabilistic method
        $start = microtime(true);
        $probResult = $number->isProbablePrime(5);
        $probTime = (microtime(true) - $start) * 1000;
        
        // Test brute force method
        $start = microtime(true);
        $bruteResult = $number->isPrimeBruteForce();
        $bruteTime = (microtime(true) - $start) * 1000;
        
        echo "Probabilistic: " . ($probResult ? "Prime" : "Composite") . " in " . round($probTime, 2) . "ms\n";
        echo "Brute Force: " . ($bruteResult ? "Prime" : "Composite") . " in " . round($bruteTime, 2) . "ms\n";
        echo "Speedup: " . round($bruteTime / $probTime, 1) . "x faster\n";
        
        $this->assertTrue($probResult);
        $this->assertTrue($bruteResult);
        
        // For small numbers, both should be fast (under 500ms in very slow CI environments)
        $this->assertTrue($probTime < 500, "Probabilistic test took too long: {$probTime}ms");
        $this->assertTrue($bruteTime < 500, "Brute force test took too long: {$bruteTime}ms");
        
        // Both methods should give correct results
        $this->assertTrue($probResult === $bruteResult, "Results should match");
        
        // Log performance for debugging
        echo "Performance: Probabilistic={$probTime}ms, Brute Force={$bruteTime}ms\n";
        
        // Additional debugging for CI environments
        if ($probTime > 100 || $bruteTime > 100) {
            echo "WARNING: Tests are running slowly - this may be a CI environment with Xdebug enabled\n";
        }
    }

    public function testMediumNumberComparison()
    {
        $number = Integer::createByString('1000000007'); // Known prime
        
        echo "\n=== Testing Medium Number (1000000007) ===\n";
        
        // Test probabilistic method
        $start = microtime(true);
        $probResult = $number->isProbablePrime(5);
        $probTime = (microtime(true) - $start) * 1000;
        
        // Test brute force method (this will take a while!)
        echo "Running brute force test (this may take several minutes)...\n";
        $start = microtime(true);
        $bruteResult = $number->isPrimeBruteForce();
        $bruteTime = (microtime(true) - $start) * 1000;
        
        echo "Probabilistic: " . ($probResult ? "Prime" : "Composite") . " in " . round($probTime, 2) . "ms\n";
        echo "Brute Force: " . ($bruteResult ? "Prime" : "Composite") . " in " . round($bruteTime, 2) . "ms\n";
        echo "Speedup: " . round($bruteTime / $probTime, 1) . "x faster\n";
        
        $this->assertTrue($probResult);
        $this->assertTrue($bruteResult);
        
        // For medium numbers, probabilistic should be significantly faster
        // Allow for more variance in CI environments with Xdebug
        $this->assertTrue($probTime < $bruteTime * 0.9, "Probabilistic should be faster for medium numbers");
        
        // Both methods should give correct results
        $this->assertTrue($probResult === $bruteResult, "Results should match");
        
        // Log performance for debugging
        echo "Performance: Probabilistic={$probTime}ms, Brute Force={$bruteTime}ms\n";
    }

    public function testLargeNumberProbabilisticOnly()
    {
        $number = Integer::createByString('123456789012345678901234567890123456789');
        
        echo "\n=== Testing Large Number (Probabilistic Only) ===\n";
        
        // Only test probabilistic method for large numbers
        $start = microtime(true);
        $probResult = $number->isProbablePrime(10);
        $probTime = (microtime(true) - $start) * 1000;
        
        echo "Number: " . $number->getStringValue() . "\n";
        echo "Digits: " . $number->getLength() . "\n";
        echo "Probabilistic: " . ($probResult ? "Prime" : "Composite") . " in " . round($probTime, 2) . "ms\n";
        echo "Brute Force: Would take approximately " . calculateBruteForceTime($number) . "\n";
        
        // Don't test brute force - it would take too long!
        $this->assertIsBool($probResult);
    }

    public function testAccuracyComparison()
    {
        echo "\n=== Testing Accuracy ===\n";
        
        // Test known primes
        $knownPrimes = ['2', '3', '5', '7', '11', '13', '17', '19', '23', '29', '31', '37'];
        $knownComposites = ['4', '6', '8', '9', '10', '12', '14', '15', '16', '18', '20', '21'];
        
        $probCorrect = 0;
        $bruteCorrect = 0;
        $total = count($knownPrimes) + count($knownComposites);
        
        // Test primes
        foreach ($knownPrimes as $primeStr) {
            $number = Integer::createByString($primeStr);
            $probResult = $number->isProbablePrime(5);
            $bruteResult = $number->isPrimeBruteForce();
            
            if ($probResult) $probCorrect++;
            if ($bruteResult) $bruteCorrect++;
        }
        
        // Test composites
        foreach ($knownComposites as $compositeStr) {
            $number = Integer::createByString($compositeStr);
            $probResult = $number->isProbablePrime(5);
            $bruteResult = $number->isPrimeBruteForce();
            
            if (!$probResult) $probCorrect++;
            if (!$bruteResult) $bruteCorrect++;
        }
        
        $probAccuracy = ($probCorrect / $total) * 100;
        $bruteAccuracy = ($bruteCorrect / $total) * 100;
        
        echo "Probabilistic Accuracy: " . round($probAccuracy, 1) . "%\n";
        echo "Brute Force Accuracy: " . round($bruteAccuracy, 1) . "%\n";
        
        $this->assertEquals(100, $bruteAccuracy); // Brute force should be 100% accurate
        $this->assertGreaterThan(95, $probAccuracy); // Probabilistic should be >95% accurate
    }
}

/**
 * Calculate estimated time for brute force primality test
 * This is a rough approximation based on number size
 */
function calculateBruteForceTime(Integer $number): string
{
    $digits = $number->getLength();
    
    // Rough approximation: O(√n) operations
    // For n digits, √n ≈ 10^(n/2)
    // Assuming each operation takes ~1 microsecond
    
    if ($digits <= 10) {
        return "seconds";
    } elseif ($digits <= 20) {
        return "minutes";
    } elseif ($digits <= 30) {
        return "hours";
    } elseif ($digits <= 40) {
        return "days";
    } else {
        return "years or more";
    }
}
