<?php

namespace Dbt\Backpack\Tests;

use Dbt\Backpack\Tests\Fixtures\Fixture;
use Dbt\Backpack\Types;

class TypesTest extends TestCase
{
    public function passingTypes (): array
    {
        return [
            ['key', 'string'],
            ['key', 'bool'],
            ['key', 'int'],
            ['key', 'float'],
            ['key', 'array'],
            ['key', 'object'],
            ['key', 'resource'],
            ['key', 'callable'],
            ['key', 'iterable'],
            ['key', 'null'],
            ['key', Fixture::class]
        ];
    }

    public function failingTypes (): array
    {
        return [
            ['key', 'flange'],
            ['key', 'BadFixture::class'],
        ];
    }

    /** @test */
    public function getting_a_key ()
    {
        $types = new Types(['test' => 'string']);

        $this->assertSame('string', $types->get('test'));
    }

    /**
     * @test
     * @dataProvider passingTypes
     */
    public function setting_a_type ($key, $type)
    {
        $types = new Types([]);

        $types->set($key, $type);

        $this->assertTrue($types->has($key));
        $this->assertSame($type, $types->get($key));
    }

    /**
     * @test
     * @dataProvider failingTypes
     */
    public function failing_to_set_a_type ($key, $type)
    {
        $this->expectException(\TypeError::class);

        (new Types([]))->set($key, $type);
    }

    /** @test */
    public function getting_all_types ()
    {
        $array = ['test' => 'string', 'testing' => 'bool'];

        $types = new Types($array);

        $this->assertSame($array, $types->all());
    }

    /** @test */
    public function mapping ()
    {
        $input = ['test' => 'string', 'testing' => 'bool'];
        $output = ['test' => 'test.string', 'testing' => 'testing.bool'];
        $types = new Types($input);

        $mapped = $types->map(function ($key, $value) {
            return sprintf('%s.%s', $key, $value);
        });

        $this->assertSame($output, $mapped);
    }

    /** @test */
    public function mapping_with_index ()
    {
        $input = ['test' => 'string', 'testing' => 'bool'];
        $count = 0;

        (new Types($input))->map(function ($key, $value, $index) use (&$count) {
            $this->assertSame($count, $index);

            $count++;
        });
    }

    /** @test */
    public function iterating_over_the_object ()
    {
        $array = ['test' => 'string', 'testing' => 'bool'];
        $types = new Types($array);

        foreach ($types as $key => $value) {
            $this->assertTrue(array_key_exists($key, $array));
            $this->assertTrue(in_array($value, $array));
        }
    }
}
