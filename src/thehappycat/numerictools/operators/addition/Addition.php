<?php namespace TheHappyCat\NumericTools\Operators\Addition;

use TheHappyCat\NumericTools\Number;

/**
 * Interface Addition
 * @package TheHappyCat\NumericTools\Operators\Addition
 */
interface Addition
{
  /**
   * @param \TheHappyCat\NumericTools\Number $a
   * @param \TheHappyCat\NumericTools\Number $b
   */
    public function add(Number $a, Number $b);
}
