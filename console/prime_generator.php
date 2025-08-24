#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TheHappyCat\NumericTools\Integer;
use TheHappyCat\NumericTools\PrimeGenerator;

/**
 * Prime Number Generator CLI
 * 
 * Usage examples:
 *   php prime_generator.php generate 256
 *   php prime_generator.php test 1000000007
 *   php prime_generator.php twin 128
 *   php prime_generator.php range 100 200
 *   php prime_generator.php sophie 64
 */

if ($argc < 2) {
    showUsage();
    exit(1);
}

$generator = new PrimeGenerator();
$command = $argv[1];

try {
    switch ($command) {
        case 'generate':
            if ($argc < 3) {
                echo "Error: Please specify bit length\n";
                showUsage();
                exit(1);
            }
            $bits = (int)$argv[2];
            $start = microtime(true);
            $prime = $generator->generatePrime($bits);
            $end = microtime(true);
            
            echo "Generated prime number:\n";
            echo "Value: " . $prime->getStringValue() . "\n";
            echo "Bits: ~" . $prime->getLength() * 3.32 . "\n";
            echo "Time: " . round(($end - $start) * 1000, 2) . "ms\n";
            break;
            
        case 'test':
            if ($argc < 3) {
                echo "Error: Please specify number to test\n";
                showUsage();
                exit(1);
            }
            $number = Integer::createByString($argv[2]);
            $start = microtime(true);
            $isPrime = $number->isPrime();
            $end = microtime(true);
            
            echo "Primality test result:\n";
            echo "Number: " . $number->getStringValue() . "\n";
            echo "Is Prime: " . ($isPrime ? "Yes" : "No") . "\n";
            echo "Time: " . round(($end - $start) * 1000, 2) . "ms\n";
            break;
            
        case 'twin':
            if ($argc < 3) {
                echo "Error: Please specify bit length\n";
                showUsage();
                exit(1);
            }
            $bits = (int)$argv[2];
            $start = microtime(true);
            list($p1, $p2) = $generator->generateTwinPrimes($bits);
            $end = microtime(true);
            
            echo "Generated twin primes:\n";
            echo "p: " . $p1->getStringValue() . "\n";
            echo "p+2: " . $p2->getStringValue() . "\n";
            echo "Time: " . round(($end - $start) * 1000, 2) . "ms\n";
            break;
            
        case 'range':
            if ($argc < 4) {
                echo "Error: Please specify start and end values\n";
                showUsage();
                exit(1);
            }
            $start = Integer::createByString($argv[2]);
            $end = Integer::createByString($argv[3]);
            $startTime = microtime(true);
            $primes = $generator->generatePrimesInRange($start, $end);
            $endTime = microtime(true);
            
            echo "Primes in range [" . $start->getStringValue() . ", " . $end->getStringValue() . "]:\n";
            echo "Count: " . count($primes) . "\n";
            echo "Primes: " . implode(', ', array_map('strval', $primes)) . "\n";
            echo "Time: " . round(($endTime - $startTime) * 1000, 2) . "ms\n";
            break;
            
        case 'sophie':
            if ($argc < 3) {
                echo "Error: Please specify bit length\n";
                showUsage();
                exit(1);
            }
            $bits = (int)$argv[2];
            $start = microtime(true);
            $prime = $generator->generateSophieGermainPrime($bits);
            $end = microtime(true);
            
            $q = $prime->multiplyBy(Integer::createByInt(2))->add(Integer::createByInt(1));
            
            echo "Generated Sophie Germain prime:\n";
            echo "p: " . $prime->getStringValue() . "\n";
            echo "2p+1: " . $q->getStringValue() . "\n";
            echo "Time: " . round(($end - $start) * 1000, 2) . "ms\n";
            break;
            
        case 'benchmark':
            if ($argc < 3) {
                echo "Error: Please specify bit length for benchmark\n";
                showUsage();
                exit(1);
            }
            $bits = (int)$argv[2];
            runBenchmark($generator, $bits);
            break;
            
        default:
            echo "Error: Unknown command '$command'\n";
            showUsage();
            exit(1);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

function showUsage(): void
{
    echo "Prime Number Generator CLI\n";
    echo "==========================\n\n";
    echo "Commands:\n";
    echo "  generate <bits>     - Generate prime with specified bit length\n";
    echo "  test <number>       - Test if number is prime\n";
    echo "  twin <bits>         - Generate twin primes\n";
    echo "  range <start> <end> - Find primes in range\n";
    echo "  sophie <bits>       - Generate Sophie Germain prime\n";
    echo "  benchmark <bits>    - Run performance benchmark\n\n";
    echo "Examples:\n";
    echo "  php prime_generator.php generate 256\n";
    echo "  php prime_generator.php test 1000000007\n";
    echo "  php prime_generator.php twin 128\n";
    echo "  php prime_generator.php range 100 200\n";
    echo "  php prime_generator.php sophie 64\n";
    echo "  php prime_generator.php benchmark 128\n";
}

function runBenchmark(PrimeGenerator $generator, int $bits): void
{
    echo "Running benchmark for $bits-bit prime generation...\n";
    echo "================================================\n\n";
    
    $times = [];
    $iterations = 5;
    
    for ($i = 1; $i <= $iterations; $i++) {
        echo "Iteration $i/$iterations... ";
        $start = microtime(true);
        $prime = $generator->generatePrime($bits);
        $end = microtime(true);
        $time = ($end - $start) * 1000;
        $times[] = $time;
        echo "Done in " . round($time, 2) . "ms\n";
    }
    
    $avgTime = array_sum($times) / count($times);
    $minTime = min($times);
    $maxTime = max($times);
    
    echo "\nBenchmark Results:\n";
    echo "==================\n";
    echo "Bit length: $bits\n";
    echo "Iterations: $iterations\n";
    echo "Average time: " . round($avgTime, 2) . "ms\n";
    echo "Min time: " . round($minTime, 2) . "ms\n";
    echo "Max time: " . round($maxTime, 2) . "ms\n";
    echo "Standard deviation: " . round(calculateStdDev($times), 2) . "ms\n";
}

function calculateStdDev(array $values): float
{
    $mean = array_sum($values) / count($values);
    $variance = 0;
    
    foreach ($values as $value) {
        $variance += pow($value - $mean, 2);
    }
    
    $variance /= count($values);
    return sqrt($variance);
}
