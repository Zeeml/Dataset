<?php

namespace Zeeml\DataSet\Tests\DataSet;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\DataSet\Instance;
use Zeeml\DataSet\DataSet\Result\Classification;
use Zeeml\DataSet\DataSet\Result\Prediction;

/**
 * Instance test case.
 */
class InstanceTest extends TestCase
{
    /**
     *
     * @var Instance
     */
    private $instance;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->instance = new Instance([9,8,7], [6,5]);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->instance = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function method_getOutputs_returns_an_array()
    {
        $this->assertInternalType('array', $this->instance->getOutputs());
        $this->assertEquals([6,5], $this->instance->getOutputs());
    }

    /**
     * @test
     */
    public function method_getOutput_returns_an_output_by_key()
    {
        $this->assertEquals(6, $this->instance->getOutput(0));
        $this->assertEquals(5, $this->instance->getOutput(1));
    }

    /**
     * @test
     */
    public function method_getOutput_returns_null_if_key_not_exists()
    {
        $this->assertNull($this->instance->getOutput(3));
    }

    /**
     * @test
     */
    public function method_getDimensions_returns_an_array()
    {
        $this->assertInternalType('array', $this->instance->getDimensions());
        $this->assertEquals([9,8,7], $this->instance->getDimensions());
    }

    /**
     * @test
     */
    public function method_getDimension_returns_an_element()
    {
        $this->assertEquals(9, $this->instance->getDimension(0));
        $this->assertEquals(8, $this->instance->getDimension(1));
        $this->assertEquals(7, $this->instance->getDimension(2));
    }

    /**
     * @test
     */
    public function method_getDimension_returns_null_if_key_not_exists()
    {
        $this->assertNull($this->instance->getDimension(3));
    }

    /**
     * @test
     * Tests Instance->result()
     */
    public function addResult()
    {
        $this->instance->addResult('test', new Prediction(2));

        $results = $this->instance->getResults();
        $this->assertCount(1, $results);
        $this->assertInstanceOf(Prediction::class, $results['test']);
        $this->assertEquals($results['test']->getValue(), 2);

        $this->instance->addResult('test2', new Classification('A'));

        $results = $this->instance->getResults();
        $this->assertCount(2, $results);

        $this->assertInstanceOf(Prediction::class, $results['test']);
        $this->assertEquals($results['test']->getValue(), 2);

        $this->assertInstanceOf(Classification::class, $results['test2']);
        $this->assertEquals($results['test2']->getValue(), 'A');
    }

    /**
     * @test
     */
    public function getResults_returns_an_array()
    {
        $this->assertInternalType('array', $this->instance->getResults());
        $this->assertCount(0, $this->instance->getResults());

        $this->instance->addResult('test', new Prediction(3));
        $this->assertCount(1, $this->instance->getResults());

    }

    /**
     * @test
     */
    public function getResult_returns_an_element_of_the_array()
    {
        $this->assertNull($this->instance->getResult(0));
        $this->instance->addResult('test', new Prediction('ABC'));
        $this->assertEquals('ABC', $this->instance->getResult('test')->getValue());
    }
}

