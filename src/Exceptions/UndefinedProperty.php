<?php

namespace Dbt\Backpack\Exceptions;

use Exception;

class UndefinedProperty extends Exception
{
    public static function of ($key, $class): Exception
    {
        return new self(
            sprintf('Undefined property %s::$%s', $class, $key)
        );
    }
}
