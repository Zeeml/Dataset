<?php

namespace Zeeml\DataSet\DataSet;

use Zeeml\DataSet\Exception\DataSetPreparationException;

class Mapper
{
    protected $dimensionKeys;
    protected $outputKeys;
    protected $hash;
    
    public function __construct(array $dimensionKeys, array $outputKeys)
    {
        $this->dimensionKeys = $dimensionKeys;
        $this->outputKeys = $outputKeys;
        $this->hash = hash('sha256', implode($dimensionKeys) . implode($outputKeys));
    }
    
    public function instanceFactory(array $dataRow, bool $preserveKeys = false): Instance
    {
        $dimensions = $outputs = [];

        foreach ($this->dimensionKeys as $dKey) {
            if (! isset($dataRow[$dKey])) {
                throw new DataSetPreparationException("No data on key $dKey");
            }
            $dimensions[] = $dataRow[$dKey];
        }

        if (count($dimensions) == 0) {
            throw new DataSetPreparationException('Data entry has wrong parameters count');
        }

        foreach ($this->outputKeys as $oKey) {
            $outputs[] = $dataRow[$oKey];
        }

        if (count($outputs) == 0) {
            throw new DataSetPreparationException('Data entry has wrong parameters count');
        }

       return new Instance($dimensions, $outputs);
    }
}
