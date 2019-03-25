<?php

namespace Dbt\Backpack\Exceptions;

use Exception;

class UndefinedProperty extends Exception
{
    public static function of (string $key, string $class): Exception
    {
        return new self(
            sprintf('Undefined property %s::$%s', $class, $key)
        );
    }
}
