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

$a = Integer::createByInt('1234567898765432123456789876543212345678987654321');
$b = Integer::createByInt('987654321234567898765432123456789');
$c = $a->add($b);
```

### Subtraction



### Multiplication



### Division



### Modulo



### Greater than



### Greater or equal to
