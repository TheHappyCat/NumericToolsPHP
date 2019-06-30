<?php namespace TheHappyCat\NumericTools\Operators\Subtraction;

use TheHappyCat\NumericTools\Number;

/**
 * Interface SubtractionInterface
 * @package TheHappyCat\NumericTools\Operators\Subtraction
 */
interface SubtractionInterface
{
    /**
     * @param Number $a
     * @param Number $b
     * @return \TheHappyCat\NumericTools\Integer
     */
    public function subtract(Number $a, Number $b);
}
