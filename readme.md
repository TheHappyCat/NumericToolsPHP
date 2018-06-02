![alt text](https://raw.githubusercontent.com/TheHappyCat/NumericTools/master/assets/mathematical.gif "Mathematical")

# *NumericToolsPHP* [![Build Status](https://travis-ci.org/TheHappyCat/NumericToolsPHP.svg?branch=master)](https://travis-ci.org/TheHappyCat/NumericToolsPHP) [![codecov](https://codecov.io/gh/TheHappyCat/NumericToolsPHP/branch/master/graph/badge.svg)](https://codecov.io/gh/TheHappyCat/NumericToolsPHP)

## A simple project created to handle large numeric operations in PHP!

Just like the normal numeric operations you would usually do, but with numbers of any size.

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
