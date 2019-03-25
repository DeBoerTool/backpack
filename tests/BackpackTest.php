<?php

namespace Dbt\Backpack\Tests;

use Dbt\Backpack\Tests\Fixtures\Fixture;

class BackpackTest extends TestCase
{
    /** @test */
    public function getting_non_backpack_properties ()
    {
        $fixture = new Fixture();

        $this->assertSame('test property', $fixture->prop);
    }

    /** @test */
    public function setting_non_backpack_properties ()
    {
        $fixture = new Fixture();

        $fixture->prop = 'it is set';

        $this->assertSame('it is set', $fixture->prop);
    }

    /** @test */
    public function setting_and_getting ()
    {
        $string = 'some string';
        $array = ['test array'];
        $fixture = new Fixture();

        $fixture->test1 = $string;
        $fixture->test2 = $array;

        $this->assertSame($string, $fixture->test1);
        $this->assertSame($array, $fixture->test2);
    }

    /** @test */
    public function failing_to_get ()
    {
        $this->expectException(\Exception::class);
        $fixture = new Fixture();

        $fixture->thisShouldFail;
    }
}
