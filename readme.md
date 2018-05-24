![alt text](https://raw.githubusercontent.com/TheHappyCat/NumericTools/master/assets/mathematical.gif "Mathematical")

# *NumericToolsPHP* [![Build Status](https://travis-ci.org/TheHappyCat/NumericToolsPHP.svg?branch=master)](https://travis-ci.org/TheHappyCat/NumericToolsPHP)

## A simple project created to handle large numeric operations in PHP!

Just like the normal numeric operations you would usually do, but with numbers of any size.

```php
<?php

$integerNumber = Integer::createByInt(1);

$smallNumber = Integer::createByString('1');
$largeNumber = Integer::createByString('987654321234567898765432123456789');
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



### Division



### Modulo



### Greater than



### Greater or equal to
