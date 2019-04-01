<?php

namespace Dbt\Backpack;

interface HasBackpack
{
    public static function getTypes(): Types;
    public function hydrate(array $items): void;
    public function isTouched (string $key): bool;
    public function getTouched (): array;
    public function getAll (): array;
}
