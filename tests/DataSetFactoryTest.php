<?php

namespace Zeeml\DataSet\Tests;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Mapper;
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

        $csvContent = [
            ['col1' => 1 , 'col2' =>  'A', 'col3' => 'I'],
            ['col1' => 2 , 'col2' => 'B',  'col3' =>'II'],
            ['col1' => 3 , 'col2' => 'C ', 'col3' => 'III'],
            ['col1' => 4 , 'col2' => 'D',  'col3' => 'IV'],
            ['col1' => 5 , 'col2' => '',   'col3' =>'V'],
            ['col1' => '', 'col2' => 'F',  'col3' =>''],
            ['col1' => 7 , 'col2' => 'G',  'col3' =>'VII'],
            ['col1' => 8 , 'col2' => 'H',  'col3' =>'VIII'],
            ['col1' => 9 , 'col2' => 'I',  'col3' =>'IX'],
            ['col1' => 10, 'col2' => 'J',  'col3' =>'X'],
            ['col1' => 1 , 'col2' => 'A',  'col3' =>'I'],
        ];

        $this->assertEquals($dataSet->getRawData(), $csvContent);
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
        $this->assertEquals($dataSet->getRawData(), $array);
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
        DataSetFactory::split($dataSet, -1);
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
        DataSetFactory::split($dataSet, 2);
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
        DataSetFactory::split($dataSet, 0.5);
    }

    /**
     * @test
     */
    public function must_be_able_to_split_correctly()
    {
        $array = [
            [1, 2],
        ];

        $dataSet = DataSetFactory::create($array);
        $dataSet->prepare(new Mapper([0], [1]));

        $dataSets = DataSetFactory::split($dataSet, 0.8);

        $this->assertCount(2, $dataSets);

        $this->assertInstanceOf(DataSet::class, $dataSets[0]);
        $this->assertInstanceOf(DataSet::class, $dataSets[1]);

        $this->assertEquals($dataSets[0]->getSize(), 1);
        $this->assertEquals($dataSets[1]->getSize(), 0);
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
            [7, 9]
        ];

        $dataSet = DataSetFactory::create($array);
        $dataSet->prepare(new Mapper([0], [1]));

        $dataSets = DataSetFactory::split($dataSet, 0.8);

        $this->assertCount(2, $dataSets);

        $this->assertInstanceOf(DataSet::class, $dataSets[0]);
        $this->assertInstanceOf(DataSet::class, $dataSets[1]);

        $this->assertEquals($dataSets[0]->getSize(), 3);
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
        $dataSet->prepare(new Mapper([0], [1]));

        $dataSets = DataSetFactory::split($dataSet, 0.7);

        $this->assertCount(2, $dataSets);

        $this->assertInstanceOf(DataSet::class, $dataSets[0]);
        $this->assertInstanceOf(DataSet::class, $dataSets[1]);

        $this->assertEquals($dataSets[0]->getSize(), 7);
        $this->assertEquals($dataSets[1]->getSize(), 3);
    }
}
