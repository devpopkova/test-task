<?php

declare(strict_types=1);

namespace App\Collection;

use App\Enum\ProductType;

class FruitCollection extends AbstractProductCollection
{
    protected function getType(): string
    {
        return ProductType::FRUIT->value;
    }
}
