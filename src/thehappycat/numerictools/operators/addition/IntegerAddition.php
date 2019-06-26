<?php namespace TheHappyCat\NumericTools\Operators\Addition;

use Exception;

use TheHappyCat\NumericTools\Operators\Addition\Addition;
use TheHappyCat\NumericTools\Integer;

class IntegerAddition implements Addition
{
    /**
     * @param \TheHappyCat\NumericTools\Integer $a
     * @param \TheHappyCat\NumericTools\Integer $b
     * @return \TheHappyCat\NumericTools\Integer
     * @throws Exception
     */
    public function add(Integer $a, Integer $b)
    {
        $comparison = sizeof($a->value) <=> sizeof($b->value);

        $top = $comparison === 0 ? $a->value : ($comparison === -1 ? $b->value : $a->value);
        $bottom = $comparison === 0 ? $b->value : ($comparison === -1 ? $a->value : $b->value);

        $indexDiff = sizeof($top) - sizeof($bottom);

        $stringHolder = '';

        $carry = 0;

        for ($i = sizeof($top) - 1; $i >= 0; $i--) {
            $intResult = ($i - $indexDiff) < 0 ? ($top[$i] + $carry) : ($top[$i] + $bottom[$i - $indexDiff] + $carry);

            $stringResult = (string) $intResult;

            if (strlen($stringResult) === 2) {
                if ($i === 0) {
                    $carry = 0;
                    $subResult = $intResult;
                } else {
                    $carry = intval($stringResult[0]);
                    $subResult = intval($stringResult[1]);
                }
            } else {
                $carry = 0;
                $subResult = intval($stringResult[0]);
            }

            $stringHolder = $subResult . $stringHolder;
        }

        return Integer::createByString($stringHolder);
    }
}
