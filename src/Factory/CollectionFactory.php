<?php

declare(strict_types=1);

namespace App\Factory;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Enum\ProductType;
use App\Exception\ProductNotFoundException;
use App\Interfaces\Collection\ProductCollectionInterface;
use App\Interfaces\Factory\CollectionFactoryInterface;

readonly class CollectionFactory implements CollectionFactoryInterface
{
    public function __construct(
        private FruitCollection $fruitCollection,
        private VegetableCollection $vegetableCollection,
    ) {
    }

    public function getCollection(string $type): ProductCollectionInterface
    {
        $type = strtolower($type);
        return match ($type) {
            ProductType::FRUIT->value => $this->fruitCollection,
            ProductType::VEGETABLE->value => $this->vegetableCollection,
            default => throw new ProductNotFoundException()
        };
    }
}
