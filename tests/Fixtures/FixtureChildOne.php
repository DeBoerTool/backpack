<?php

namespace Dbt\Backpack\Tests\Fixtures;

use Dbt\Backpack\Types;

/**
 * @property string test1
 * @property array test2
 */
class FixtureChildOne extends Fixture
{
    public static function types (): Types
    {
        return new Types([
            'test1' => 'float',
        ]);
    }
}
