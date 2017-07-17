<?php

namespace Zeeml\DataSet\Tests\Processor;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Processor\ArrayProcessor;

class ArrayProcessorTest extends TestCase
{
    /**
     * @dataProvider getData
     * @param $dataSource
     * @param $expectedDataSet
     */
    public function test_read($dataSource, $expectedDataSet)
    {
        $processor = new ArrayProcessor($dataSource);
        $processor->read();

        $this->assertEquals($expectedDataSet, $processor->data());
    }

    /**
     * @dataProvider getData
     * @param $dataSource
     * @param $expectedDataSet
     */
    public function test_populate($dataSource, $expectedDataSet)
    {
        $processor = new ArrayProcessor($dataSource);
        $processor->populate();

        $this->assertEquals($expectedDataSet, $processor->data());
        $this->assertEquals(count($expectedDataSet), $processor->size());
    }

    /**
     * @dataProvider getData
     * @param $dataSource
     * @param $expectedDataSet
     */
    public function test_get_data_and_size($dataSource, $expectedDataSet)
    {
        $processor = new ArrayProcessor($dataSource);

        $this->assertEquals($expectedDataSet, $processor->data());
        $this->assertEquals(count($expectedDataSet), $processor->size());
    }

    public function getData()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ]
            ],
            [
                [
                    [[1], 2, 3],
                    [4, [5], 6],
                    [7, 8, [9]],
                ],
                []
            ],
            [
                [
                    [ 'hello', 'this' ],
                    [ 'is', 'my' ],
                    [ new class(){}, [3] ],
                ],
                [
                    [ 'hello', 'this' ],
                    [ 'is', 'my' ],
                ]
            ],
            [
                [
                    [ ['hello'], ['these'] ],
                    [ 'are', 'not' ],
                    [ [new class(){}], ['numeric'] ],
                ],
                [
                    [ 'are', 'not' ]
                ]
            ],
        ];
    }
}
