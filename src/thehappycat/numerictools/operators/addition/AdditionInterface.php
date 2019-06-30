<?php namespace TheHappyCat\NumericTools\Operators\Addition;

use TheHappyCat\NumericTools\Number;

/**
 * Interface AdditionInterface
 * @package TheHappyCat\NumericTools\Operators\Addition
 */
interface AdditionInterface
{
    /**
     * @param Number $a
     * @param Number $b
     * @return \TheHappyCat\NumericTools\Integer
     */
    public function add(Number $a, Number $b);
}
