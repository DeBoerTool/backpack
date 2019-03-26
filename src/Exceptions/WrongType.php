<?php

namespace Dbt\Backpack\Exceptions;

use TypeError;

class WrongType extends TypeError
{
    public static function of (string $key, string $type): self
    {
        return new self(sprintf(
            'Type of property "%s" must be "%s".',
            $key,
            $type
        ));
    }
}
