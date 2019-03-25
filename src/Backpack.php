<?php declare(strict_types=1);

namespace Dbt\Backpack;

trait Backpack
{
    protected $backpack = [];

    abstract protected function types (): Types;

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

        $this->noSuchKey($key);
    }

    protected function noSuchKey (string $key)
    {
        throw new \Exception(
            sprintf('Undefined property %s::$%s', get_class($this), $key)
        );
    }
}
