<?php

namespace Zeeml\DataSet\Tests\Core\Result;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Result\Classification;

class ClassificationTest extends TestCase
{
    /**
     * @test
     */
    public function should_work_fine()
    {
        $classification = new Classification(1, 2);

        $this->assertEquals($classification->getValue(), 1);
        $this->assertEquals($classification->getConfidence(), 2);
        $this->assertCount(0, $classification->getProbabilities());

        $classification->addProbability(0, 0.33);

        //value and confidence should not have changed
        $this->assertEquals($classification->getValue(), 1);
        $this->assertEquals($classification->getConfidence(), 2);

        $this->assertEquals($classification->getProbabilities(), [ 0 => 0.33]);
        $this->assertEquals(0.33, $classification->getProbability(0));

        $classification->addProbability(1, 0.45);

        //value and confidence should not have changed
        $this->assertEquals($classification->getValue(), 1);
        $this->assertEquals($classification->getConfidence(), 2);

        $this->assertEquals($classification->getProbabilities(), [ 0 => 0.33, 1 => 0.45]);
        $this->assertEquals(0.33, $classification->getProbability(0));
        $this->assertEquals(0.45, $classification->getProbability(1));

    }
}