<?php

namespace Zeeml\Dataset\Dataset;

use Zeeml\Dataset\Exception\DatasetPreparationException;

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
    
    public function instancesFactory(array $data, bool $preserveKeys = false)
    {
        $_ = [];

        foreach ($data as $key => $val) {
            
            $dimensions = $outputs = [];
            
            foreach ($this->dimensionKeys as $dKey) {
                if (! isset($val[$dKey])) {
                    throw new DatasetPreparationException("No data on key $dKey");
                }
                $dimensions[$preserveKeys ? $dKey : count($dimensions)] = $val[$dKey];
            }
            
            if (count($dimensions) == 0) {
                throw new DatasetPreparationException(
                    sprintf("Data entry %d has wrong parameters count", $key)
                );
            }

            foreach ($this->outputKeys as $oKey) {
                $outputs[$preserveKeys ? $oKey : count($outputs)] = $val[$oKey];
            }
            
            $_[$key] = new Instance($dimensions, $outputs);
        }
        
        return $_;
    }
}
