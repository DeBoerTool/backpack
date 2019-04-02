<?php

namespace Dbt\Backpack\Tests;

use Dbt\Backpack\Tests\Fixtures\Fixture;
use Dbt\Backpack\Tests\Fixtures\FixtureChildOne;

class BackpackExtensionTest extends TestCase
{
    /** @test */
    public function child_classes_dont_clash ()
    {
        $fixture = new Fixture();
        $child = new FixtureChildOne();

        $fixture->test1 = 'a string';
        $child->test1 = 1.23;

        $this->assertSame('a string', $fixture->test1);
        $this->assertSame(1.23, $child->test1);
    }
}
