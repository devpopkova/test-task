<?php

declare(strict_types=1);

namespace App\Interfaces\Factory;

use App\Entity\Product;

interface ProductFactoryInterface
{
    /**
     * @param array{
     *      name: string,
     *      quantity: float,
     *      type: string,
     *      unit: string
     *  } $data
     */
    public function createFromArray(array $data): Product;
}
