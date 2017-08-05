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
     * Expects $dimensionParams to be [ index_of_dimension => CleanPolicy , ... ]
     * Expects $outputParams to be    [ index_of_output    => CleanPolicy , ... ]
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
     * @param array $dataRow
     * @return null|array
     * @throws DataSetPreparationException
     */
    public function map(array $dataRow)
    {
        $dimensions = $outputs = [];

        //For the dimensions
        foreach ($this->dimensionParams as $dimKey => $cleanPolicy) {
            //if no cleanPolicy is specified, then $cleanPolicy is the index itself, ex :  [1 => CleanPolicy::none(), 2]
            if (! is_callable($cleanPolicy)) {
                $dimKey = $cleanPolicy;
            }

            //If no column found at the specified index throw an exception
            if (! array_key_exists($dimKey, $dataRow)) {
                throw new DataSetPreparationException('Column at index \'' . $dimKey . '\' not found');
            }

            $value = $dataRow[$dimKey];
            //Check if the cleanPolicy is a callable then apply it
            //If the policy returns false, the row is skipped and no instance is created
            if (is_callable($cleanPolicy) && ! $cleanPolicy($value)) {
                return null;
            }

            //add the value to the dimensions array
            $dimensions[$dimKey] =  $value;
        }

        //Doing the same for the outputs
        foreach ($this->outputParams as $outputKey => $cleanPolicy) {
            //if no cleanPolicy is specified, then $cleanPolicy is probably the index itself, ex :  [1 => CleanPolicy::none(), 2]
            if (! is_callable($cleanPolicy)) {
                $outputKey = $cleanPolicy;
            }

            if (! array_key_exists($outputKey, $dataRow)) {
                throw new DataSetPreparationException('Column at index \'' . $outputKey . '\' not found');
            }

            $value = $dataRow[$outputKey];

            //Check if the cleanPolicy is a callable then apply it
            //If the policy returns false, the row is skipped and no instanceis created
            if (is_callable($cleanPolicy) && ! $cleanPolicy($value)) {
                return null;
            }

            //add the value to the dimensions array
            $outputs[$outputKey] =  $value;
        }

        return [$dimensions, $outputs];
    }
}
