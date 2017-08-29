<?php

namespace Zeeml\DataSet\Tests\Core;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Policy;

/**
 * Class Policy
 */
class PolicyTest extends TestCase
{
    public function test_none()
    {
        $nonePolicy = Policy::none();

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
        $skipPolicy = Policy::skip();

        //should be a callable
        $this->assertTrue(is_callable($skipPolicy));

        //should return true if value is not empty and should never alter any value
        //Integers
        $value = 5;
        $key = 0;
        $row = [5];
        $this->assertTrue($skipPolicy($value, $key, $row));
        $this->assertEquals(5, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([5], $row);
        //floats
        $value = 3.9;
        $key = 0;
        $row = [3.9];
        $this->assertTrue($skipPolicy($value, $key, $row));
        $this->assertEquals(3.9, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([3.9], $row);
        //strings
        $value = 'Abc';
        $key = 0;
        $row = ['Abc'];
        $this->assertTrue($skipPolicy($value, $key, $row));
        $this->assertEquals('Abc', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals(['Abc'], $row);

        //should return false if value is empty but should not alter any value
        //NULL value
        $value = null;
        $key = 0;
        $row = [1];
        $this->assertFalse($skipPolicy($value));
        $this->assertEquals(null, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //FALSE value
        $value = false;
        $key = 0;
        $row = [1];
        $this->assertFalse($skipPolicy($value));
        $this->assertEquals(false, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //Empty string
        $value = '';
        $key = 0;
        $row = [1];
        $this->assertFalse($skipPolicy($value));
        $this->assertEquals('', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
    }

    public function test_replaceWith()
    {
        $replaceWithPolicy = Policy::replaceWith('replacement');

        //should be a callable
        $this->assertTrue(is_callable($replaceWithPolicy));

        //should return true if value is not empty and should not replace values
        //Integers
        $value = 5;
        $key = 0;
        $row = [5];
        $this->assertTrue($replaceWithPolicy($value, $key, $row));
        $this->assertEquals(5, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([5], $row);
        //floats
        $value = 3.9;
        $key = 0;
        $row = [3.9];
        $this->assertTrue($replaceWithPolicy($value, $key, $row));
        $this->assertEquals(3.9, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([3.9], $row);
        //strings
        $value = 'Abc';
        $key = 0;
        $row = ['Abc'];
        $this->assertTrue($replaceWithPolicy($value, $key, $row));
        $this->assertEquals('Abc', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals(['Abc'], $row);

        //should replace empty values with 'replacement'
        //NULL value
        $value = null;
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('replacement', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //FALSE value
        $value = false;
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('replacement', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //Empty string
        $value = '';
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithPolicy($value));
        $this->assertEquals('replacement', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
    }

    public function test_replaceWithAvg()
    {
        $replaceWithAvgPolicy = Policy::replaceWithAvg();

        //should be a callable
        $this->assertTrue(is_callable($replaceWithAvgPolicy));

        //should return true if value is not empty and should not replace values
        //Integers
        $value = 5;
        $key = 0;
        $row = [5];
        $this->assertTrue($replaceWithAvgPolicy($value, $key, $row));
        $this->assertEquals(5, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([5], $row);
        //floats
        $value = 3.9;
        $key = 0;
        $row = [3.9];
        $this->assertTrue($replaceWithAvgPolicy($value, $key, $row));
        $this->assertEquals(3.9, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([3.9], $row);
        //strings
        $value = 'Abc';
        $key = 0;
        $row = ['Abc'];
        $this->assertTrue($replaceWithAvgPolicy($value, $key, $row));
        $this->assertEquals('Abc', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals(['Abc'], $row);

        //should return true if value is empty and replace it with AVG const
        //NULL value
        $value = null;
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithAvgPolicy($value));
        $this->assertEquals(Policy::AVG, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //FALSE value
        $value = false;
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithAvgPolicy($value));
        $this->assertEquals(Policy::AVG, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //Empty string
        $value = '';
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithAvgPolicy($value));
        $this->assertEquals(Policy::AVG, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
    }

    public function test_replaceWithMostCommon()
    {
        $replaceWithMostCommon = Policy::replaceWithMostCommon();

        //should be a callable
        $this->assertTrue(is_callable($replaceWithMostCommon));

        //should return true if value is not empty and should not replace values
        //Integers
        $value = 5;
        $key = 0;
        $row = [5];
        $this->assertTrue($replaceWithMostCommon($value, $key, $row));
        $this->assertEquals(5, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([5], $row);
        //floats
        $value = 3.9;
        $key = 0;
        $row = [3.9];
        $this->assertTrue($replaceWithMostCommon($value, $key, $row));
        $this->assertEquals(3.9, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([3.9], $row);
        //strings
        $value = 'Abc';
        $key = 0;
        $row = ['Abc'];
        $this->assertTrue($replaceWithMostCommon($value, $key, $row));
        $this->assertEquals('Abc', $value);
        $this->assertEquals(0, $key);
        $this->assertEquals(['Abc'], $row);

        //should return true if value is empty and replace it with MOST_COMMON const
        //NULL value
        $value = null;
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithMostCommon($value));
        $this->assertEquals(Policy::MOST_COMMON, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //FALSE value
        $value = false;
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithMostCommon($value));
        $this->assertEquals(Policy::MOST_COMMON, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
        //Empty string
        $value = '';
        $key = 0;
        $row = [1];
        $this->assertTrue($replaceWithMostCommon($value));
        $this->assertEquals(Policy::MOST_COMMON, $value);
        $this->assertEquals(0, $key);
        $this->assertEquals([1], $row);
    }

    public function test_rename()
    {
        $renamePolicy = Policy::rename('new_name');

        //should be a callable
        $this->assertTrue(is_callable($renamePolicy));

        //should always rename keys and return true
        //Integers
        $value = 5;
        $key = 0;
        $row = [5];
        $this->assertTrue($renamePolicy($value, $key, $row));
        $this->assertEquals(5, $value);
        $this->assertEquals('new_name', $key);
        $this->assertEquals([5], $row);
        //floats
        $value = 3.9;
        $key = 0;
        $row = [3.9];
        $this->assertTrue($renamePolicy($value, $key, $row));
        $this->assertEquals(3.9, $value);
        $this->assertEquals('new_name', $key);
        $this->assertEquals([3.9], $row);
        //strings
        $value = 'Abc';
        $key = 0;
        $row = ['Abc'];
        $this->assertTrue($renamePolicy($value, $key, $row));
        $this->assertEquals('Abc', $value);
        $this->assertEquals('new_name', $key);
        $this->assertEquals(['Abc'], $row);
        //NULL value
        $value = null;
        $key = 0;
        $row = [1];
        $this->assertTrue($renamePolicy($value, $key, $row));
        $this->assertEquals(null, $value);
        $this->assertEquals('new_name', $key);
        $this->assertEquals([1], $row);
        //FALSE value
        $value = false;
        $key = 0;
        $row = [1];
        $this->assertTrue($renamePolicy($value, $key, $row));
        $this->assertEquals(false, $value);
        $this->assertEquals('new_name', $key);
        $this->assertEquals([1], $row);
        //Empty string
        $value = '';
        $key = 0;
        $row = [1];
        $this->assertTrue($renamePolicy($value, $key, $row));
        $this->assertEquals('', $value);
        $this->assertEquals('new_name', $key);
        $this->assertEquals([1], $row);
    }
}


