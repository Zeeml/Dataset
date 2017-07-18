<?php

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\DataSet\Instance;

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
     * Tests Instance->dimensions()
     * @test
     */
    public function method_dimensions_returns_an_array()
    {
        $this->assertInternalType('array', $this->instance->dimensions());
        $this->assertEquals([9,8,7], $this->instance->dimensions());
    }

    /**
     * Tests Instance->outputs()
     * @test
     */
    public function method_outputs_returns_an_array()
    {
        $this->assertInternalType('array', $this->instance->outputs());
        $this->assertEquals([6,5], $this->instance->outputs());
    }

    /**
     * Tests Instance->dimension($index)
     * @test
     */
    public function method_output_returns_an_element()
    {
        $this->assertEquals(6, $this->instance->output(0));
        $this->assertEquals(5, $this->instance->output(1));
        $this->assertNull($this->instance->output(3));
    }

    /**
     * Tests Instance->output($index)
     * @test
     */
    public function method_dimension_returns_an_element()
    {
        $this->assertEquals(9, $this->instance->dimension(0));
        $this->assertEquals(8, $this->instance->dimension(1));
        $this->assertEquals(7, $this->instance->dimension(2));
        $this->assertNull($this->instance->dimension(3));
    }

    /**
     * Tests Instance->result()
     */
    public function testResult()
    {
        // TODO Auto-generated InstanceTest->testResult()
        $this->markTestIncomplete("result test not implemented");
        
        $this->instance->result(/* parameters */);
    }

    /**
     * Tests Instance->results()
     */
    public function testResults()
    {
        // TODO Auto-generated InstanceTest->testResults()
        $this->markTestIncomplete("results test not implemented");
        
        $this->instance->results(/* parameters */);
    }
}

