<?php declare(strict_types=1);

namespace Dbt\Backpack;

use Dbt\Backpack\Exceptions\MissingKey;
use Dbt\Backpack\Exceptions\UndefinedProperty;
use Dbt\Backpack\Exceptions\WrongType;

trait Backpack
{
    protected $backpack = [];
    protected static $types = null;

    abstract protected static function types (): Types;

    public static function getTypes (): Types
    {
        if (static::$types === null) {
            static::$types = static::types();
        }

        return static::$types;
    }

    public function hydrate (array $values): void
    {
        $index = -1;

        // When hydrating, require each key to be present on the given array
        // of values so there are no undefined values present.
        foreach (static::getTypes()->all() as $key => $type) {
            $index++;

            switch (true) {
                case isset($values[$key]):
                    $this->$key = $values[$key];
                    break;
                case isset($values[$index]):
                    $this->$key = $values[$index];
                    break;
                default:
                    throw MissingKey::of($key, get_class($this));
            }
        }
    }

    public function __set ($key, $value)
    {
        if (static::getTypes()->has($key)) {
            $this->validateAndSet($key, $value);
            return;
        }

        throw UndefinedProperty::of($key, get_class($this));
    }

    public function __get ($key)
    {
        if (static::getTypes()->has($key)) {
            return $this->backpack[$key];
        }

        throw UndefinedProperty::of($key, get_class($this));
    }

    protected function validateAndSet (string $key, $value)
    {
        if (static::getTypes()->is($key, $value)) {
            $this->backpack[$key] = $value;
            return;
        }

        throw WrongType::of($key, static::getTypes()->get($key));
    }
}
