<?php
namespace Zeeml\DataSet;

use Zeeml\DataSet\Exception\WrongUsageException;

class DataSetFactory
{
    static public function create($source)
    {
        if (is_array($source)) {
            $processorType = 'Array';
        } else if (is_file($source)) {
            $processorType = pathinfo($source, PATHINFO_EXTENSION);
        }

        $processor = sprintf("%s\\Processor\\%sProcessor", 
            __NAMESPACE__, 
            ucfirst(strtolower($processorType ?? ''))
        );

        if (! class_exists($processor)) {
            throw new WrongUsageException('Can not handle data from given source : ' . (is_string($source) ? $source : gettype($source)));
        }

        try {
            return new DataSet(new $processor($source));
        } catch (\Throwable $e) {
            throw new WrongUsageException('The dataSet could not be created : ' . $e->getMessage());
        }
    }
}
