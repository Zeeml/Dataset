<?php

namespace Zeeml\DataSet\Processor;

use PHPUnit\Framework\TestCase;

class CsvProcessorTest extends TestCase
{
    protected $cvsFileName;
    protected $expectedResult;

    public function setUp()
    {
        parent::setUp();
        $this->cvsFileName = __DIR__ .  DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'data.csv';
        $this->expectedResult = [
            [1, 'A', 'I'],
            [2, 'B', 'II'],
            [3, 'C ' , 'III'],
            [4, 'D', 'IV'],
            [5, 'E', 'V'],
            [6, 'F', 'VI'],
            [7, 'G', 'VII'],
            [8, 'H', 'VIII'],
            [9, 'I', 'IX'],
            [10, 'J','X'],
        ];
    }

    public function test_read()
    {
        $processor = new CsvProcessor($this->cvsFileName);
        $processor->read();

        $this->assertEquals($processor->data(), $this->expectedResult);
    }

    public function test_populate()
    {
        $processor = new CsvProcessor($this->cvsFileName);
        $processor->populate();

        $this->assertEquals($this->expectedResult, $processor->data());
        $this->assertEquals(count($this->expectedResult), $processor->size());
    }

    public function test_get_data_and_size()
    {
        $processor = new CsvProcessor($this->cvsFileName);

        $this->assertEquals($this->expectedResult, $processor->data());
        $this->assertEquals(count($this->expectedResult), $processor->size());
    }
}
