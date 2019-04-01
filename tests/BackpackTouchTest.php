<?php

namespace Dbt\Backpack\Tests;

use Dbt\Backpack\Tests\Fixtures\Fixture;

class BackpackTouchTest extends TestCase
{
    /** @test */
    public function hydrating_skips_touch ()
    {
        $fixture = new Fixture();

        $fixture->hydrate([
            'test1' => 'this is a test string',
            'test2' => [],
        ]);

        $this->assertFalse($fixture->isTouched('test1'));

        $fixture->test1 = 'this is another test string';

        $this->assertTrue($fixture->isTouched('test1'));
    }

    /** @test */
    public function getting_only_touched_props ()
    {
        $fixture = new Fixture();

        $fixture->hydrate([
            'test1' => 'this is a test string',
            'test2' => [],
        ]);

        $this->assertCount(2, $fixture->getAll());
        $this->assertCount(0, $fixture->getTouched());

        $fixture->test1 = 'this will be touched';

        $this->assertCount(1, $fixture->getTouched());
        $this->assertSame(
            'this will be touched',
            $fixture->getTouched()['test1']
        );
    }
}
