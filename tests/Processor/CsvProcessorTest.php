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
            ['col1' => 1,  'col2'  => 'A',   'col3' => 'I'],
            ['col1' => 2,  'col2'  => 'B',   'col3' => 'II'],
            ['col1' => 3,  'col2'  => 'C ',  'col3' => 'III'],
            ['col1' => 4,  'col2'  => 'D',   'col3' => 'IV'],
            ['col1' => 5,  'col2'  => '',    'col3' => 'V'],
            ['col1' => '', 'col2'  => 'F',   'col3' => ''],
            ['col1' => 7,  'col2'  => 'G',   'col3' => 'VII'],
            ['col1' => 8,  'col2'  => 'H',   'col3' => 'VIII'],
            ['col1' => 9,  'col2'  => 'I',   'col3' => 'IX'],
            ['col1' => 10, 'col2'  => 'J',   'col3' => 'X'],
            ['col1' => 1,  'col2'  => 'A',   'col3' => 'I'],
        ];
    }

    public function test_read()
    {
        $processor = new CsvProcessor($this->cvsFileName);
        $data = $processor->read();

        $this->assertEquals($data, $this->expectedResult);
    }
}
