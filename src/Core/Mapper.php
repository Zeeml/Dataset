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
        $this->dimensionKeys = $dimensionKeys;
        $this->outputKeys = $outputKeys;
    }

    /**
     * Creates an Instane class from an array using the dimensionKeys and outputKeys specified in the construct
     * @param array $dataRow
     * @return Instance
     * @throws DataSetPreparationException
     */
    public function createInstance(array $dataRow): Instance
    {
        $dimensions = $outputs = [];

        foreach ($this->dimensionKeys as $dKey) {
            if (! isset($dataRow[$dKey])) {
                throw new DataSetPreparationException("No data on key $dKey");
            }
            $dimensions[] = $dataRow[$dKey];
        }

        if (count($dimensions) == 0) {
            throw new DataSetPreparationException('Dimensions have wrong parameters count (0) ');
        }

        foreach ($this->outputKeys as $oKey) {
            if (! isset($dataRow[$oKey])) {
                throw new DataSetPreparationException("No data on key $oKey");
            }
            $outputs[] = $dataRow[$oKey];
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
