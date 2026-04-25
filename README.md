![php-big-integer](https://raw.githubusercontent.com/jpruiz114/php-big-integer/master/assets/mathematical.gif "Mathematical")

# *php-big-integer* [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

## A simple project created to handle large numeric operations in PHP!

Just like the normal numeric operations you would usually do, but with numbers of any size.

## Requirements

- **PHP:** 8.5, matching `composer.json` (`"php": "^8.5"`) and the `Dockerfile` image (`php:8.5-cli-bookworm`). The `^8.5` range allows compatible 8.x versions; it does not allow PHP 9.0 until you change that requirement.
- **Composer:** for installing dependencies when developing the library or running tests locally.
- **Docker (optional):** to build the image described under [Docker](#docker) instead of using a local PHP install.

## Installation

### Via Composer (recommended)

```bash
composer require jpruiz114/php-big-integer
```

### Manual installation

1. Clone this repository:

   ```bash
   git clone https://github.com/jpruiz114/php-big-integer.git
   cd php-big-integer
   ```

2. Install dependencies (runtime and **dev** dependencies such as PHPUnit):

   ```bash
   composer install
   ```

## Docker

The image uses **PHP 8.5** and **Composer**; install [Docker](https://docs.docker.com/get-docker/) locally. From the **repository root**:

**Build and run (default: PHPUnit via `composer test`)**

```bash
docker build -t php-big-integer .

docker run --rm -e XDEBUG_MODE=off php-big-integer
```

**Lock file**

`docker build` runs `composer install` using `composer.json` and `composer.lock`. If you change PHP or dev dependencies in `composer.json`, run `composer update` (or the relevant `composer require`) so `composer.lock` matches, commit the lock file, then rebuild. The same applies if the build fails with *lock file is not up to date* or a package *does not satisfy your constraint*.

**Refresh the lock file without local Composer** (writes `composer.lock` and `vendor/` into your project directory)

```bash
docker run --rm -v "$(pwd):/app" -w /app php:8.5-cli-bookworm bash -c \
  'apt-get update -qq && apt-get install -y --no-install-recommends curl unzip git -qq \
   && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
   && composer update --no-interaction --prefer-dist'
```

**Other commands**

```bash
# Shell in the container
docker run --rm -it -e XDEBUG_MODE=off php-big-integer sh

# HTML coverage under ./coverage on the host
docker run --rm -e XDEBUG_MODE=coverage -v "$(pwd)/coverage:/app/coverage" php-big-integer \
  ./vendor/bin/phpunit --coverage-html coverage/
```

For CPU-heavy commands like prime generation, keep Xdebug off for better performance:

```bash
docker run --rm -e XDEBUG_MODE=off php-big-integer php console/prime_generator.php generate 256
```

## Quick start

After `composer require jpruiz114/php-big-integer` (or this repository’s `composer install`), the `Integer` class is available in the `TheHappyCat\NumericTools` namespace.

```php
<?php

use TheHappyCat\NumericTools\Integer;

$integerNumber = Integer::createByInt(1);

$smallNumber = Integer::createByString('1');
$largeNumber = Integer::createByString('987654321234567898765432123456789');

// A really large number that as primitive type might throw a number in scientific notation or infinity.
$reallyLargeNumber = Integer::createByString('12345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321');
```

The **Operations** and **Advanced usage** examples use `Integer` the same way. Add `use TheHappyCat\NumericTools\Integer;` at the top of your file (or use the fully qualified class name).

## Operations

### Addition

```php
<?php

$a = Integer::createByString('1234567898765432123456789876543212345678987654321');
$b = Integer::createByString('987654321234567898765432123456789');

// $c = 1234567898765433111111111111111111111111111111110
$c = $a->add($b);
```

### Subtraction

```php
<?php

$a = Integer::createByString('1234567898765432123456789876543212345678987654321');
$b = Integer::createByString('987654321234567898765432123456789');

// $c = 1234567898765431135802468641975313580246864197532
$c = $a->subtract($b);

$a = Integer::createByString('987654321234567898765432123456789');
$b = Integer::createByString('1234567898765432123456789876543212345678987654321');

// $c = -1234567898765431135802468641975313580246864197532
$c = $a->subtract($b);
```

### Multiplication

```php
<?php

$a = Integer::createByString('999999999999');
$b = Integer::createByString('789');

// $c = 788999999999211
$c = $a->multiplyBy($b);

$a = Integer::createByString('1234567898765432123456789876543212345678987654321');
$b = Integer::createByString('987654321234567898765432123456789');

// $c = 1219326320073159600060966114921506736777910409998442005792202408166072245112635269
$c = $a->multiplyBy($b);
```

### Division

```php
<?php

$dividend = Integer::createByString('987654321234567898765432123456789');
$divisor = Integer::createByString('12345678987654321');

// $quotient = 80000000180000000
$quotient = $dividend->divideBy($divisor);
```

### Modulo

```php
<?php

$dividend = Integer::createByString("1234567890123456789");
$divisor = Integer::createByString("9876543210");

// $module = 8626543209
$module = $dividend->mod($divisor);
```

### Prime number testing

```php
<?php

// Check if a number is prime
$number = Integer::createByString("1000000007");
$isPrime = $number->isPrime(); // true

// Probabilistic primality test (faster for large numbers)
$largeNumber = Integer::createByString("123456789012345678901234567890123456789");
$isProbablePrime = $largeNumber->isProbablePrime(10); // true/false with 99.9%+ accuracy

// Test known composite numbers
$composite = Integer::createByString("1000000008");
$isComposite = !$composite->isPrime(); // true
```

### Number theory

```php
<?php

// Greatest Common Divisor
$a = Integer::createByString("48");
$b = Integer::createByString("18");
$gcd = $a->gcd($b); // 6

// Least Common Multiple
$lcm = $a->lcm($b); // 144

// Modular Exponentiation (essential for cryptography)
$base = Integer::createByString("2");
$exponent = Integer::createByString("1000");
$modulus = Integer::createByString("1000000007");
$result = $base->modPow($exponent, $modulus); // 2^1000 mod 1000000007

// Square Root (integer part)
$sqrt = Integer::createByString("100")->sqrt(); // 10

// Power of 2 check
$isPowerOfTwo = Integer::createByString("64")->isPowerOfTwo(); // true
```

### Prime number generation

```php
<?php

use TheHappyCat\NumericTools\PrimeGenerator;

$generator = new PrimeGenerator();

// Generate a 256-bit prime number
$prime = $generator->generatePrime(256);

// Generate twin primes (p, p+2 where both are prime)
list($p1, $p2) = $generator->generateTwinPrimes(128);

// Find the next prime after a given number
$nextPrime = $generator->generateNextPrime(Integer::createByString("1000"));

// Find all primes in a range
$primes = $generator->generatePrimesInRange(
    Integer::createByString("100"),
    Integer::createByString("200")
);

// Generate Sophie Germain prime (p where 2p+1 is also prime)
$sophiePrime = $generator->generateSophieGermainPrime(64);

// Generate random prime in a range
$randomPrime = $generator->generateRandomPrimeInRange(
    Integer::createByString("1000"),
    Integer::createByString("10000")
);
```

### Command line interface

From the **repository root** after `composer install` (the script loads `vendor/autoload.php`):

```bash
# Generate a 256-bit prime
php console/prime_generator.php generate 256

# Test if a number is prime
php console/prime_generator.php test 1000000007

# Generate twin primes
php console/prime_generator.php twin 128

# Find primes in a range
php console/prime_generator.php range 100 200

# Generate Sophie Germain prime
php console/prime_generator.php sophie 64

# Run performance benchmark
php console/prime_generator.php benchmark 128
```

### Greater than

```php
<?php

$a = Integer::createByString("123456789012345678901234567890");
$b = Integer::createByString("987654321");

// true
$comparison = $a->greaterThan($b);

$a = Integer::createByString("987654321");
$b = Integer::createByString("123456789012345678901234567890");

// false
$comparison = $a->greaterThan($b);
```

### Greater or equal to

```php
<?php

$a = Integer::createByString("1500");
$b = Integer::createByString("1492");

// true
$comparison = $a->greaterOrEqualTo($b);

$a = Integer::createByString("1234567890");
$b = Integer::createByString("1234567890");

// true
$comparison = $a->greaterOrEqualTo($b);

$a = Integer::createByString("1234");
$b = Integer::createByString("1234567890");

// false
$comparison = $a->greaterOrEqualTo($b);
```

## Advanced usage

### Working with extremely large numbers

```php
<?php

// Calculate factorial of large numbers
function factorial($n) {
    $result = Integer::createByString('1');
    for ($i = 2; $i <= $n; $i++) {
        $result = $result->multiplyBy(Integer::createByString((string)$i));
    }
    return $result;
}

// Calculate 100! (factorial of 100)
$factorial100 = factorial(100);
echo $factorial100->toString(); // Outputs a very long number
```

### Mathematical series

```php
<?php

// Calculate sum of first n natural numbers
function sumOfNaturalNumbers($n) {
    $sum = Integer::createByString('0');
    for ($i = 1; $i <= $n; $i++) {
        $sum = $sum->add(Integer::createByString((string)$i));
    }
    return $sum;
}

$sum = sumOfNaturalNumbers(1000000);
echo $sum->toString();
```

## Testing

From the project root, after `composer install`:

**Composer scripts (from `composer.json`)**

```bash
composer test
composer test:coverage   # HTML report under coverage/
```

**Direct `phpunit` invocations**

```bash
./vendor/bin/phpunit
./vendor/bin/phpunit --coverage-html coverage/
```

**CI and Docker**

- **Docker:** the image default is `composer test` (`php vendor/bin/phpunit` with `phpunit.xml`).
- **CI:** `.github/workflows/php.yml` runs `vendor/bin/phpunit` with Clover output for Codecov.

## Contributing

1. Fork the repository.
2. Create a feature branch.
3. Make your changes.
4. Add tests for new functionality.
5. Ensure all tests pass.
6. Submit a pull request.

## License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

## Author

**Jean Paul Ruiz** — [jpruiz114@gmail.com](mailto:jpruiz114@gmail.com)

## Acknowledgments

- Inspired by the need to handle large numbers in PHP applications.
- Built with modern PHP practices and comprehensive testing.
