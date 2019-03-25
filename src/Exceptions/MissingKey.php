<?php

namespace Dbt\Backpack\Exceptions;

use Exception;

class MissingKey extends Exception
{
    public static function of (string $key, string $class): Exception
    {
        return new self(
            sprintf('Missing key "%s" while hydrating %s', $key, $class)
        );
    }
}
