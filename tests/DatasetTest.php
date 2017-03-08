<?php

use Zeeml\Dataset\Dataset;
use PHPUnit\Framework\TestCase;
use Zeeml\Dataset\Processor\AbstractProcessor;

/**
 * Dataset test case.
 */
class DatasetTest extends TestCase
{
    /**
     *
     * @var Dataset
     */
    private $dataset;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->dataset = Dataset::factory(
            __DIR__ . '/fixtures/data.csv', 
            Dataset::PREDICTION
        );
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->dataset = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function method_size_return_an_integer()
    {
        $this->assertEquals(10, $this->dataset->size());
    }

    /**
     * @test
     */
    public function method_get_returns_a_data_array()
    {
        $this->assertInternalType('array', $this->dataset->get());
        $this->assertEquals(10, count($this->dataset->get()));
    }
    
    /**
     * @test
     */
    public function method_processor_returns_an_instance_of_a_processor()
    {
        $this->assertInstanceOf(AbstractProcessor::class, $this->dataset->processor());
    }
}
