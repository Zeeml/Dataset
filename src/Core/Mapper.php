<?php

namespace Zeeml\DataSet\Core;
use Zeeml\DataSet\Exception\DataSetPreparationException;

/**
 * Class Mapper that creates instances from a single row of an array
 * @package Zeeml\Core\Core
 */
class Mapper
{
    protected $dimensionParams;
    protected $outputParams;

    /**
     * Mapper constructor.
     * Expects $dimensionParams to be [ index_of_dimension => [Policy1, Policy2, ...]  ]
     * Expects $outputParams to be    [ index_of_output    => [Policy1] ]
     * @param array $dimensionParams
     * @param array $outputParams
     */
    public function __construct(array $dimensionParams, array $outputParams)
    {
        $this->dimensionParams = $dimensionParams;
        $this->outputParams = $outputParams;
    }

    /**
     * Creates an Instance class from an array using the dimensionKeys and outputKeys specified in the construct
     * @param array $row
     * @return null|array
     * @throws DataSetPreparationException
     */
    public function map(array &$row)
    {
        $dimensions = $this->parse($this->dimensionParams, $row);
        $outputs = $this->parse($this->outputParams, $row);


        return $dimensions && $outputs ? [$dimensions, $outputs] : null;
    }

    public function parse(&$params, &$row)
    {
        $_ = [];
        foreach ($params as $key => $policies) {

            //if one Policy is specified, turn it into an array
            if (is_callable($policies)) {
                $policies = [$policies];
            }

            //if the policies is not an array, then no Policy was specified, the Policy is the index itself (example: new Mapper(['col1'], ['col2']))
            if (! is_array($policies)) {
                $key = $policies;
                $policies = [Policy::none()];
            }

            //If no column found at the specified index throw an exception
            if (! array_key_exists($key, $row)) {
                throw new DataSetPreparationException('Column at index \'' . $key . '\' not found');
            }

            $value = $row[$key];

            //Apply all the policies one after the other and perform an && between them, if one policy returns false, the whole line is skipped
            if (! array_reduce(
                $policies,
                function($current, $policy) use (&$value, &$key, &$row) {
                    //Check if the Policy is a callable, if not the line is skipped
                    if (!is_callable($policy)) {
                        return false;
                    }
                    //Ssave the old value in order to put the old one back and not the new one
                    $originalValue = $value;
                    //Execute the policy which might change the value and/or the key
                    $result = $policy($value, $key);
                    //If the key is altered, create a new key ( for example : array = ['col1' => 1, 'col2' => 2] , 'col1' was renamed into 'element1' then = array = ['col1' => 1, 'col2' => 2, 'element1' => 1] )
                    $row[$key] = $originalValue;

                    return $current && $result;
                },
                true //start with $current == true
            )) {
                return null;
            }

            //add the value to the dimensions array
            $_[$key] =  $value;
        }

        return $_;
    }
}
