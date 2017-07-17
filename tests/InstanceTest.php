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
        $this->instance = new Instance([0,1,2], [3,4]);
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
        $this->assertEquals([0,1,2], $this->instance->dimensions());
    }

    /**
     * Tests Instance->outputs()
     * @test
     */
    public function method_outputs_returns_an_array()
    {
        $this->assertInternalType('array', $this->instance->outputs());
        $this->assertEquals([3,4], $this->instance->outputs());
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

