<?php declare(strict_types=1);

namespace Dbt\Backpack;

use Closure;
use Dbt\TypeChecker\Alias;
use Dbt\TypeChecker\Type;
use TypeError;

final class Types
{
    /** @var array */
    private $types;

    public function __construct (array $types)
    {
        $this->types = [];
        $this->setMany($types);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function hasOk (string $key, $value): bool
    {
        return $this->has($key) && $this->is($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function is (string $key, $value): bool
    {
        return Type::test($value, $this->get($key));
    }

    public function get (string $key): string
    {
        return $this->types[$key];
    }

    public function set (string $key, string $type): void
    {
        if (!Alias::isValid($type)) {
            throw new TypeError(
                sprintf('%s is not a valid type', $type)
            );
        }

        $this->types[$key] = $type;
    }

    public function has (string $key): bool
    {
        return array_key_exists($key, $this->types);
    }

    public function all (): array
    {
        return $this->types;
    }

    public function map (Closure $callback): array
    {
        $keys = array_keys($this->all());
        $values = array_values($this->all());

        return array_combine(
            $keys,
            array_map($callback, $keys, $values)
        );
    }

    public function setMany (array $types): void
    {
        foreach ($types as $key => $type) {
            $this->set($key, $type);
        }
    }
}
