<?php declare(strict_types=1);

namespace Dbt\Backpack;

use Dbt\Backpack\Exceptions\MissingKey;
use Dbt\Backpack\Exceptions\UndefinedProperty;
use Dbt\Backpack\Exceptions\WrongType;

trait Backpack
{
    protected $backpack = [];
    protected $touched = [];
    protected $types = null;

    abstract protected static function types (): Types;

    public function getTypes (): Types
    {
        if ($this->types === null) {
            $this->types = static::types();
        }

        return $this->types;
    }

    public function hydrate (array $values): void
    {
        $index = -1;

        // When hydrating, require each key to be present on the given array
        // of values so there are no undefined values present. Hydrating is
        // intended as a constructor step, so it skips touching.
        foreach ($this->getTypes()->all() as $key => $type) {
            $index++;

            switch (true) {
                case isset($values[$key]):
                    $this->setWithoutTouch($key, $values[$key]);
                    break;
                case isset($values[$index]):
                    $this->setWithoutTouch($key, $values[$index]);
                    break;
                default:
                    throw MissingKey::of($key, get_class($this));
            }
        }
    }

    public function __set ($key, $value)
    {
        if ($this->getTypes()->has($key)) {
            $this->setWithTouch($key, $value);
            return;
        }

        throw UndefinedProperty::of($key, get_class($this));
    }

    public function __get ($key)
    {
        if ($this->getTypes()->has($key)) {
            return $this->backpack[$key];
        }

        throw UndefinedProperty::of($key, get_class($this));
    }

    /**
     * Has the given key been touched?
     * @param string $key
     * @return bool
     */
    public function isTouched (string $key): bool
    {
        return in_array($key, $this->touched);
    }

    /**
     * Get the backpack's touched keys and associated values only.
     * @return array
     */
    public function getTouched (): array
    {
        return array_filter(
            $this->getAll(),
            function ($key) {
                return $this->isTouched($key);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Get the backpack's keys and values.
     * @return array
     */
    public function getAll (): array
    {
        return $this->backpack;
    }

    /**
     * Validate the type of a given key/value pair.
     * @param string $key
     * @param mixed $value
     */
    protected function validate (string $key, $value): void
    {
        if (!$this->getTypes()->is($key, $value)) {
            throw WrongType::of($key, $this->getTypes()->get($key));
        }
    }

    /**
     * Set a value with touch.
     * @param string $key
     * @param mixed $value
     */
    protected function setWithTouch (string $key, $value): void
    {
        $this->validate($key, $value);
        $this->touch($key);
        $this->backpack[$key] = $value;
    }

    /**
     * Set a value without touch.
     * @param string $key
     * @param $value
     */
    protected function setWithoutTouch (string $key, $value): void
    {
        $this->validate($key, $value);
        $this->backpack[$key] = $value;
    }

    /**
     * Flag a key as touched.
     * @param string $key
     */
    protected function touch (string $key): void
    {
        if ($this->hasKey($key)) {
            $this->touched[] = $key;
        }
    }

    /**
     * Does the backpack have a given key?
     * @param string $key
     * @return bool
     */
    protected function hasKey (string $key): bool
    {
        return isset($this->backpack[$key]);
    }
}
