<?php

namespace Dbt\Backpack;

interface HasBackpack
{
    public function hydrate(array $items): void;
}
