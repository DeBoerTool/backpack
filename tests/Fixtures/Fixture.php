<?php

namespace Dbt\Backpack\Tests\Fixtures;

use Dbt\Backpack\Backpack;
use Dbt\Backpack\HasBackpack;
use Dbt\Backpack\Types;

/**
 * @property string test1
 * @property array test2
 */
class Fixture implements HasBackpack
{
    use Backpack;

    public $prop = 'test property';

    protected static function types (): Types
    {
        return new Types([
            'test1' => 'string',
            'test2' => 'array',
        ]);
    }
}
