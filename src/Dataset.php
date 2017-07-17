<?php
namespace Zeeml\Dataset;

use Zeeml\Dataset\Exception\FileNotFoundException;
use Zeeml\Dataset\Exception\UnknownDatasetTypeException;
use Zeeml\Dataset\Processor\ArrayProcessor;

class Dataset
{
    const PREDICTION = 'Prediction';
    
    const CLASSIFICATION = 'Classification';
    
    static public function factory(string $uri, string $type)
    {
        if (! in_array($type, [self::CLASSIFICATION, self::PREDICTION], $type)) {
            throw new UnknownDatasetTypeException($type . " is not a supported dataset type");
        }
        
        // @todo add win support
        if (substr($uri, 0, 1) == DIRECTORY_SEPARATOR) {
            // this is a local file
            if (! file_exists($uri)) {
                throw new FileNotFoundException($uri . " doesn't point to a file");
            }
            
            $ext = substr($uri, strrpos($uri, '.')+1);
        } else {
            // @todo implement URI parsing for DB/WS
            die ('not yet available');
        }
            
        $processor = sprintf("%s\\Processor\\%sProcessor", 
            __NAMESPACE__, 
            ucfirst(strtolower($ext))
        );
        
        $dataset = sprintf('Zeeml\\Dataset\\%sDataset', $type);
        
        try {
            return new $dataset(new $processor($uri));
        } catch (\Exception $e) {
            
        }
    }
    
    static public function fromArray(string $type, array $array)
    {
        if (! in_array($type, [self::CLASSIFICATION, self::PREDICTION], $type)) {
            throw new UnknownDatasetTypeException($type . " is not a supported dataset type");
        }
        
        $processor = new ArrayProcessor($array);
        $dataset = $type . 'Dataset';
        
        return new $dataset($processor); 
    }
}
