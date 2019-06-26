<?php namespace TheHappyCat\NumericTools\Operators\Addition;

use TheHappyCat\NumericTools\Integer;

interface Addition
{
  /**
   * @param \TheHappyCat\NumericTools\Integer $a
   * @param \TheHappyCat\NumericTools\Integer $b
   */
    public function add(Integer $a, Integer $b);
}
