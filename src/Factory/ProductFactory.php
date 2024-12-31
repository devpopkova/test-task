<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Product;
use App\Enum\UnitType;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Utils\WeightConverter;

class ProductFactory implements ProductFactoryInterface
{
    public function __construct(
        private WeightConverter $weightConverter
    ) {
    }

    /**
     * @param array{
     *      name: string,
     *      quantity: float,
     *      type: string,
     *      unit: string
     *  } $data
     */
    public function createFromArray(
        array $data
    ): Product {
        $quantity = $this->weightConverter->convertToGrams($data['quantity'], $data['unit']);

        $product = new Product();
        $product
            ->setName($data['name'])
            ->setType(strtolower($data['type']))
            ->setQuantity($quantity)
            ->setUnit(UnitType::GRAMS->value);

        return $product;
    }
}
