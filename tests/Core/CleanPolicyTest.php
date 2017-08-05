<?php

namespace Zeeml\DataSet\Tests\Core;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\CleanPolicy;

/**
 * Class CleanPolicy
 */
class CleanPolicyTest extends TestCase
{
    public function test_none()
    {
        $nonePolicy = CleanPolicy::none();

        //should be a callable
        $this->assertTrue(is_callable($nonePolicy));

        //should always return true
        $this->assertTrue($nonePolicy());

        //should not alter the value
        $value = 5;
        $nonePolicy($value, 6);
        $this->assertEquals(5, $value);
        $value = 3.8;
        $nonePolicy($value, 6);
        $this->assertEquals(3.8, $value);
        $value = 'A';
        $nonePolicy($value, 6);
        $this->assertEquals('A', $value);
        $value = null;
        $nonePolicy($value, 6);
        $this->assertEquals(null, $value);
        $value = false;
        $nonePolicy($value, 6);
        $this->assertEquals(false, $value);
        $value = [];
        $nonePolicy($value, 6);
        $this->assertEquals([], $value);

    }

    public function test_skip()
    {
        $skipPolicy = CleanPolicy::skip();

        //should be a callable
        $this->assertTrue(is_callable($skipPolicy));

        //should return true if value is not empty
        $value = 5;
        $this->assertTrue($skipPolicy($value));
        $value = 3.9;
        $this->assertTrue($skipPolicy($value));
        $value = 'A';
        $this->assertTrue($skipPolicy($value));

        //should return false if value is empty
        $value = null;
        $this->assertFalse($skipPolicy($value));
        $value = false;
        $this->assertFalse($skipPolicy($value));
        $value = '';
        $this->assertFalse($skipPolicy($value));

        //should not alter the value
        $value = 5;
        $skipPolicy($value, 6);
        $this->assertEquals(5, $value);
        $value = 3.8;
        $skipPolicy($value, 6);
        $this->assertEquals(3.8, $value);
        $value = 'A';
        $skipPolicy($value, 6);
        $this->assertEquals('A', $value);
        $value = null;
        $skipPolicy($value, 6);
        $this->assertEquals(null, $value);
        $value = false;
        $skipPolicy($value, 6);
        $this->assertEquals(false, $value);
        $value = [];
        $skipPolicy($value, 6);
        $this->assertEquals([], $value);
    }

    public function test_replaceWith()
    {
        $replaceWithPolicy = CleanPolicy::replaceWith('ok');

        //should be a callable
        $this->assertTrue(is_callable($replaceWithPolicy));

        //should always return true and

        //Should not replace non empty values
        $value = 5;
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals(5, $value);
        $value = 3.9;
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals(3.9, $value);
        $value = 'A';
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('A', $value);

        //Should alter value when empty
        $value = null;
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('ok', $value);
        $value = false;
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('ok', $value);
        $value = [];
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('ok', $value);
        $value = '';
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('ok', $value);
    }

    public function test_replaceWithAvg()
    {
        $replaceWithPolicyAvg = CleanPolicy::replaceWithAvg();

        //should be a callable
        $this->assertTrue(is_callable($replaceWithPolicyAvg));

        //should always return true and

        //Should not replace non empty values
        $value = 5;
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals(5, $value);
        $value = 3.9;
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals(3.9, $value);
        $value = 'A';
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals('A', $value);

        //Should alter value when empty
        $value = null;
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals(CleanPolicy::AVG, $value);
        $value = false;
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals(CleanPolicy::AVG, $value);
        $value = [];
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals(CleanPolicy::AVG, $value);
        $value = '';
        $this->assertTrue($replaceWithPolicyAvg($value));
        $this->assertEquals(CleanPolicy::AVG, $value);
    }
}


