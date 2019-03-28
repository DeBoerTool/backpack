<?php

namespace Dbt\Backpack;

interface HasBackpack
{
    public static function getTypes(): Types;
    public function hydrate(array $items): void;
}
