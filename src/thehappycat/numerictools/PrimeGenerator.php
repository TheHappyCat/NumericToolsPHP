<?php namespace TheHappyCat\NumericTools;

use Exception;

/**
 * Class PrimeGenerator
 * Generates prime numbers using various algorithms
 * 
 * @package TheHappyCat\NumericTools
 */
class PrimeGenerator
{
    /**
     * Small primes for trial division optimization
     */
    private const SMALL_PRIMES = [3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97];
    
    /**
     * Generate a random odd number with specified bit length
     * 
     * @param int $bits Number of bits
     * @return Integer
     */
    public function generateRandomOdd(int $bits): Integer
    {
        if ($bits < 2) {
            throw new Exception("Bit length must be at least 2");
        }
        
        // Generate random bytes
        $bytes = random_bytes(ceil($bits / 8));
        $hex = bin2hex($bytes);
        
        // Convert to binary string
        $binary = '';
        for ($i = 0; $i < strlen($hex); $i++) {
            $binary .= str_pad(decbin(hexdec($hex[$i])), 4, '0', STR_PAD_LEFT);
        }
        
        // Truncate to exact bit length
        $binary = substr($binary, 0, $bits);
        
        // Ensure it's odd (set least significant bit to 1)
        if (substr($binary, -1) === '0') {
            $binary = substr($binary, 0, -1) . '1';
        }
        
        // Ensure it's not too small (set most significant bit to 1)
        if (substr($binary, 0, 1) === '0') {
            $binary = '1' . substr($binary, 1);
        }
        
        // Convert binary to decimal string
        $decimal = base_convert($binary, 2, 10);
        
        return Integer::createByString($decimal);
    }
    
    /**
     * Generate a prime number with specified bit length
     * 
     * @param int $bits Number of bits
     * @param int $certainty Number of Miller-Rabin iterations
     * @return Integer
     * @throws Exception
     */
    public function generatePrime(int $bits, int $certainty = 10): Integer
    {
        if ($bits < 2) {
            throw new Exception("Bit length must be at least 2");
        }
        
        $attempts = 0;
        $maxAttempts = 1000;
        
        while ($attempts < $maxAttempts) {
            $candidate = $this->generateRandomOdd($bits);
            
            // Quick trial division by small primes
            if ($this->passesTrialDivision($candidate)) {
                // Use Miller-Rabin for final testing
                if ($candidate->isProbablePrime($certainty)) {
                    return $candidate;
                }
            }
            
            $attempts++;
        }
        
        throw new Exception("Failed to generate prime after $maxAttempts attempts");
    }
    
    /**
     * Generate twin primes (p, p+2 where both are prime)
     * 
     * @param int $bits Number of bits for each prime
     * @param int $certainty Number of Miller-Rabin iterations
     * @return array [p, p+2]
     * @throws Exception
     */
    public function generateTwinPrimes(int $bits, int $certainty = 10): array
    {
        $attempts = 0;
        $maxAttempts = 5000;
        
        while ($attempts < $maxAttempts) {
            $p = $this->generatePrime($bits, $certainty);
            $p2 = $p->add(Integer::createByInt(2));
            
            if ($p2->isProbablePrime($certainty)) {
                return [$p, $p2];
            }
            
            $attempts++;
        }
        
        throw new Exception("Failed to generate twin primes after $maxAttempts attempts");
    }
    
    /**
     * Generate a prime number greater than a given number
     * 
     * @param Integer $start Starting number
     * @param int $certainty Number of Miller-Rabin iterations
     * @return Integer
     */
    public function generateNextPrime(Integer $start, int $certainty = 10): Integer
    {
        $candidate = $start->add(Integer::createByInt(1));
        
        // Ensure candidate is odd
        if ($candidate->mod(Integer::createByInt(2))->isZero()) {
            $candidate = $candidate->add(Integer::createByInt(1));
        }
        
        while (true) {
            if ($candidate->isProbablePrime($certainty)) {
                return $candidate;
            }
            $candidate = $candidate->add(Integer::createByInt(2));
        }
    }
    
    /**
     * Generate primes in a range
     * 
     * @param Integer $start Starting number
     * @param Integer $end Ending number
     * @return array Array of prime numbers
     */
    public function generatePrimesInRange(Integer $start, Integer $end): array
    {
        $primes = [];
        $current = $start;
        
        // Ensure we start with an odd number
        if ($current->mod(Integer::createByInt(2))->isZero()) {
            $current = $current->add(Integer::createByInt(1));
        }
        
        while ($current->lessThanOrEqualTo($end)) {
            if ($current->isProbablePrime(5)) {
                $primes[] = $current;
            }
            $current = $current->add(Integer::createByInt(2));
        }
        
        return $primes;
    }
    
    /**
     * Quick trial division test using small primes
     * 
     * @param Integer $number Number to test
     * @return bool True if passes trial division
     */
    private function passesTrialDivision(Integer $number): bool
    {
        foreach (self::SMALL_PRIMES as $prime) {
            if ($number->mod(Integer::createByInt($prime))->isZero()) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Generate a random prime in a range
     * 
     * @param Integer $min Minimum value
     * @param Integer $max Maximum value
     * @param int $certainty Number of Miller-Rabin iterations
     * @return Integer
     * @throws Exception
     */
    public function generateRandomPrimeInRange(Integer $min, Integer $max, int $certainty = 10): Integer
    {
        $attempts = 0;
        $maxAttempts = 1000;
        
        while ($attempts < $maxAttempts) {
            $candidate = $this->generateRandomInRange($min, $max);
            
            if ($candidate->isProbablePrime($certainty)) {
                return $candidate;
            }
            
            $attempts++;
        }
        
        throw new Exception("Failed to generate prime in range after $maxAttempts attempts");
    }
    
    /**
     * Generate a random number in a range
     * 
     * @param Integer $min Minimum value
     * @param Integer $max Maximum value
     * @return Integer
     */
    private function generateRandomInRange(Integer $min, Integer $max): Integer
    {
        $range = $max->subtract($min);
        $bits = $range->getLength() * 3.32; // log2 approximation
        
        do {
            $random = $this->generateRandomOdd((int)$bits);
            $candidate = $min->add($random->mod($range));
        } while ($candidate->greaterThan($max));
        
        return $candidate;
    }
    
    /**
     * Generate a Sophie Germain prime (p where 2p+1 is also prime)
     * 
     * @param int $bits Number of bits
     * @param int $certainty Number of Miller-Rabin iterations
     * @return Integer
     * @throws Exception
     */
    public function generateSophieGermainPrime(int $bits, int $certainty = 10): Integer
    {
        $attempts = 0;
        $maxAttempts = 2000;
        
        while ($attempts < $maxAttempts) {
            $p = $this->generatePrime($bits, $certainty);
            $q = $p->multiplyBy(Integer::createByInt(2))->add(Integer::createByInt(1));
            
            if ($q->isProbablePrime($certainty)) {
                return $p;
            }
            
            $attempts++;
        }
        
        throw new Exception("Failed to generate Sophie Germain prime after $maxAttempts attempts");
    }
}
