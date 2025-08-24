![alt text](https://raw.githubusercontent.com/TheHappyCat/NumericTools/master/assets/mathematical.gif "Mathematical")

# *NumericToolsPHP* [![Total Downloads](https://poser.pugx.org/thehappycat/numerictools/downloads)](https://packagist.org/packages/thehappycat/numerictools) [![License](https://poser.pugx.org/thehappycat/numerictools/license)](https://packagist.org/packages/thehappycat/numerictools)

## A simple project created to handle large numeric operations in PHP!

Just like the normal numeric operations you would usually do, but with numbers of any size.

## Requirements

- **PHP**: 8.0 or higher (tested with PHP 8.4)
- **Composer**: For dependency management

## Installation

### Via Composer (Recommended)

```bash
composer require thehappycat/numerictools
```

### Manual Installation

1. Clone this repository:
```bash
git clone https://github.com/TheHappyCat/NumericToolsPHP.git
cd NumericToolsPHP
```

2. Install dependencies:
```bash
composer install
```

## Quick Start

```php
<?php

$integerNumber = Integer::createByInt(1);

$smallNumber = Integer::createByString('1');
$largeNumber = Integer::createByString('987654321234567898765432123456789');

// A really large number that as primitive type might throw a number in scientific notation or infinity.
$reallyLargeNumber = Integer::createByString('12345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321234567898765432123456789876543212345678987654321');
```

## Operations currently supported

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

### Prime Number Testing

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

### Number Theory Operations

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

## Advanced Usage Examples

### Working with Extremely Large Numbers

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

### Mathematical Series

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

Run the test suite to ensure everything works correctly:

```bash
# Run all tests
./vendor/bin/phpunit

# Run with coverage report
./vendor/bin/phpunit --coverage-html coverage/
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author

**Jean Paul Ruiz** - [jpruiz114@gmail.com](mailto:jpruiz114@gmail.com)

## Acknowledgments

- Inspired by the need to handle large numbers in PHP applications
- Built with modern PHP practices and comprehensive testing
