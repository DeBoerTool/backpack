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

    public function getting_all_types ()
    {
        $array = ['test' => 'string', 'testing' => 'bool'];

        $types = new Types($array);

        $this->assertSame($array, $types->all());
    }
}
