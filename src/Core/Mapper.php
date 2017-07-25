<?php

namespace Zeeml\DataSet\Core;

use Zeeml\DataSet\Exception\DataSetPreparationException;

/**
 * Class Mapper that creates instances from a single row of an array
 * @package Zeeml\Core\Core
 */
class Mapper
{
    protected $dimensionKeys;
    protected $outputKeys;

    /**
     * Mapper constructor.
     * @param array $dimensionKeys
     * @param array $outputKeys
     */
    public function __construct(array $dimensionKeys, array $outputKeys)
    {
        $this->dimensionKeys = array_values($dimensionKeys);
        $this->outputKeys = array_values($outputKeys);
    }

    /**
     * Creates an Instance class from an array using the dimensionKeys and outputKeys specified in the construct
     * @param array $dataRow
     * @param bool $preserveKeys
     * @return Instance
     * @throws DataSetPreparationException
     */
    public function createInstance(array $dataRow, bool $preserveKeys): Instance
    {
        $dimensions = $outputs = [];

        foreach ($this->dimensionKeys as $index => $dKey) {
            if (! isset($dataRow[$dKey])) {
                throw new DataSetPreparationException("No data on key $dKey");
            }

            $dimensions[$preserveKeys ? $dKey : $index] = $dataRow[$dKey];
        }

        if (count($dimensions) == 0) {
            throw new DataSetPreparationException('Dimensions have wrong parameters count (0) ');
        }

        foreach ($this->outputKeys as $index => $oKey) {
            if (! isset($dataRow[$oKey])) {
                throw new DataSetPreparationException("No data on key $oKey");
            }
            $outputs[$preserveKeys ? $oKey : $index] = $dataRow[$oKey];
        }

        if (count($outputs) == 0) {
            throw new DataSetPreparationException('outputs have wrong parameters count or not the same as dimensions');
        }

        return new Instance($dimensions, $outputs);
    }

    /**
     * Getter for the output keys
     * @return array
     */
    public function getOutputKeys(): array
    {
        return $this->outputKeys;
    }

    /**
     * Getter for the dimension keys
     * @return array
     */
    public function getDimensionKeys(): array
    {
        return $this->dimensionKeys;
    }
}
