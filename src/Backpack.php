<?php declare(strict_types=1);

namespace Dbt\Backpack;

use Dbt\Backpack\Exceptions\MissingKey;
use Dbt\Backpack\Exceptions\UndefinedProperty;

trait Backpack
{
    protected $backpack = [];

    abstract protected function types (): Types;

    public function hydrate (array $values)
    {
        // When hydrating, require each key to be present on the given array
        // of values so there are no undefined values present.
        foreach ($this->types()->all() as $key => $type) {
            if (isset($values[$key])) {
                $this->$key = $values[$key];
                continue;
            }

            throw MissingKey::of($key, get_class($this));
        }
    }

    public function __set ($key, $value)
    {
        if ($this->types()->hasOk($key, $value)) {
            $this->backpack[$key] = $value;
        }

        // This replicates PHP's behaviour.
        $this->$key = $value;
    }

    public function __get ($key)
    {
        if ($this->types()->has($key)) {
            return $this->backpack[$key];
        }

        throw UndefinedProperty::of($key, get_class($this));
    }
}
