<?php

namespace Zeeml\DataSet\Tests;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\DataSet;
use Zeeml\DataSet\DataSetFactory;

class DataSetFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create_from_csv()
    {
        $csvPath = __DIR__ .  DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'data.csv';

        $dataSet = DataSetFactory::create($csvPath);
        $this->assertInstanceOf(DataSet::class, $dataSet);

        $csvContent = array_map('str_getcsv', file($csvPath));
        array_shift($csvContent);

        $this->assertEquals($dataSet->getData(), $csvContent);
    }

    /**
     * @test
     */
    public function create_from_array()
    {
        $array = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $dataSet = DataSetFactory::create($array);

        $this->assertInstanceOf(DataSet::class, $dataSet);
        $this->assertEquals($dataSet->getData(), $array);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\WrongUsageException
     */
    public function split_DataSet_fails_with_negative_split()
    {
        $array = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $dataSet = DataSetFactory::create($array);
        DataSetFactory::splitDataSet($dataSet, -1);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\WrongUsageException
     */
    public function split_DataSet_fails_with_split_greater_than_1()
    {
        $array = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $dataSet = DataSetFactory::create($array);
        DataSetFactory::splitDataSet($dataSet, 2);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function split_DataSet_fails_with_unprepared_dataSet()
    {
        $array = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $dataSet = DataSetFactory::create($array);
        DataSetFactory::splitDataSet($dataSet, 0.5);
    }

    /**
     * @test
     */
    public function split_dataSet_should_split_it_in_two_dataSets()
    {
        $array = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $dataSet = DataSetFactory::create($array);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $dataSets = DataSetFactory::splitDataSet($dataSet, 0.5);

        $this->assertCount(2, $dataSets);

        $this->assertInstanceOf(DataSet::class, $dataSets[0]);
        $this->assertInstanceOf(DataSet::class, $dataSets[1]);

        $this->assertEquals($dataSets[0]->getSize(), 2);
        $this->assertEquals($dataSets[1]->getSize(), 1);


        $array = [
            [1, 2],
            [3, 4],
            [5, 6],
            [7, 8],
            [9, 10],
            [11, 12],
            [13, 14],
            [15, 16],
            [17, 18],
            [19, 20],
        ];

        $dataSet = DataSetFactory::create($array);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $dataSets = DataSetFactory::splitDataSet($dataSet, 0.7);

        $this->assertCount(2, $dataSets);

        $this->assertInstanceOf(DataSet::class, $dataSets[0]);
        $this->assertInstanceOf(DataSet::class, $dataSets[1]);

        $this->assertEquals($dataSets[0]->getSize(), 7);
        $this->assertEquals($dataSets[1]->getSize(), 3);
    }
}
