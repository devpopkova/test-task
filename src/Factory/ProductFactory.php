<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Product;
use App\Interfaces\Factory\ProductFactoryInterface;

class ProductFactory implements ProductFactoryInterface
{
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
        $product = new Product();
        $product
            ->setName($data['name'])
            ->setType($data['type'])
            ->setQuantity($data['quantity'])
            ->setUnit($data['unit']);

        return $product;
    }
}
