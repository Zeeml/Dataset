<?php

namespace Zeeml\DataSet\Tests;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\DataSet;
use Zeeml\DataSet\DataSetFactory;
use Zeeml\DataSet\Processor\ArrayProcessor;
use Zeeml\DataSet\Processor\CsvProcessor;

class DataSetFactoryTest extends TestCase
{
    public function test_create_from_csv()
    {
        $dataSet = DataSetFactory::create(
            __DIR__ .  DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'data.csv'
        );

        $this->assertInstanceOf(DataSet::class, $dataSet);
        $this->assertInstanceOf(CsvProcessor::class, $dataSet->processor());
    }

    public function test_create_from_array()
    {
        $dataSet = DataSetFactory::create(
            [
                [ [1], [3] ],
                [ [1], [3] ],
                [ [1], [3] ],
            ]
        );

        $this->assertInstanceOf(DataSet::class, $dataSet);
        $this->assertInstanceOf(ArrayProcessor::class, $dataSet->processor());
    }
}
