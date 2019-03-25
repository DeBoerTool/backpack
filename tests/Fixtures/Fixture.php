<?php

namespace Dbt\Backpack\Tests\Fixtures;

use Dbt\Backpack\Backpack;
use Dbt\Backpack\Types;

/**
 * @property string test1
 * @property array test2
 */
class Fixture
{
    use Backpack;

    public $prop = 'test property';

    protected function types (): Types
    {
        return new Types([
            'test1' => 'string',
            'test2' => 'array',
        ]);
    }
}
