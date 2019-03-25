<?php declare(strict_types=1);

namespace Dbt\Backpack;

use Dbt\TypeChecker\Alias;
use Dbt\TypeChecker\Type;
use TypeError;

final class Types
{
    private $types;

    public function __construct (array $types)
    {
        $this->types = [];
        $this->setMany($types);
    }

    public function hasOk (string $key, $value): bool
    {
        return $this->has($key) && $this->is($key, $value);
    }

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

    public function setMany (array $types): void
    {
        foreach ($types as $key => $type) {
            $this->set($key, $type);
        }
    }
}
