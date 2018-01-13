<?php

require_once __DIR__ . '/../src/thehappycat/numerictools/Integer.php';
require_once __DIR__ . '/../src/thehappycat/numerictools/NumberValidations.php';

use TheHappyCat\NumericTools\Integer;

$dividend = Integer::createByInt(59580);
$divisor = Integer::createByInt(30);

echo PHP_EOL . 'dividend' . ' = ' . strval($dividend);
echo PHP_EOL . 'divisor' . '  = ' . strval($divisor);

$maxMultiplier = $dividend->getMaximumMultiplier($divisor);
echo PHP_EOL . 'maxMultiplier' . ' = ' . strval($maxMultiplier);

echo PHP_EOL . '----- ----- ----- ----- -----';

/* ***** */

$dividend = Integer::createByInt(7);
$divisor = Integer::createByInt(3);

echo PHP_EOL . 'dividend' . ' = ' . strval($dividend);
echo PHP_EOL . 'divisor' . '  = ' . strval($divisor);

$maxMultiplier = $dividend->getMaximumMultiplier($divisor);
echo PHP_EOL . 'maxMultiplier' . ' = ' . strval($maxMultiplier);

echo PHP_EOL . '----- ----- ----- ----- -----';

/* ***** */

$dividend = Integer::createByInt(7);
$divisor = Integer::createByInt(2);

echo PHP_EOL . 'dividend' . ' = ' . strval($dividend);
echo PHP_EOL . 'divisor' . '  = ' . strval($divisor);

$maxMultiplier = $dividend->getMaximumMultiplier($divisor);
echo PHP_EOL . 'maxMultiplier' . ' = ' . strval($maxMultiplier);

echo PHP_EOL . '----- ----- ----- ----- -----';

/* ***** */

$dividend = Integer::createByInt(7);
$divisor = Integer::createByInt(1);

echo PHP_EOL . 'dividend' . ' = ' . strval($dividend);
echo PHP_EOL . 'divisor' . '  = ' . strval($divisor);

$maxMultiplier = $dividend->getMaximumMultiplier($divisor);
echo PHP_EOL . 'maxMultiplier' . ' = ' . strval($maxMultiplier);

echo PHP_EOL . '----- ----- ----- ----- -----';
