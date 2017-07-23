<?php
namespace Zeeml\DataSet;

use Zeeml\DataSet\Exception\DataSetPreparationException;
use Zeeml\DataSet\Exception\WrongUsageException;

class DataSetFactory
{
    /**
     * Creates a dataSet from a source (csv, array, url ...)
     * @param $source
     * @return DataSet
     * @throws WrongUsageException
     */
    static public function create($source): DataSet
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

    /**
     * creates a sub Core containing $split % (expressed between 0 and 1) of the dataSet sent
     * the dataSet sent MUST be prepared
     * @param DataSet $dataSet
     * @param float $split
     * @return array
     * @throws WrongUsageException
     * @throws DataSetPreparationException
     */
    public static function splitDataSet(DataSet $dataSet, float $split): array
    {
        if ($split <= 0 || $split > 1) {
            throw new WrongUsageException('The split must be between 0 and 1');
        }

        $data = $dataSet->getData();
        $dataCount = floor($dataSet->getSize() * $split);
        if ($dataCount == 0) {
            $dataCount++;
        }
        $randomKeys = array_rand($data, $dataCount);
        $randomKeys = is_array($randomKeys)? $randomKeys : [$randomKeys];
        $data1 = $data2 = [];

        foreach ($data as $index => $row) {
            if (in_array($index, $randomKeys)) {
                $data1[] = $row;
            } else {
                $data2[] = $row;
            }
        }


        $dataSet1 = self::create($data1);
        $dataSet1->prepare($dataSet->getMapper());

        $dataSet2 = self::create($data2);
        $dataSet2->prepare($dataSet->getMapper());

        return [$dataSet1, $dataSet2];
    }
}
