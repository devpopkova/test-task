<?php

declare(strict_types=1);

namespace App\Interfaces\Collection;

use App\DTO\ProductOutputDTO;
use App\Entity\Product;

interface ProductCollectionInterface
{
    public function add(Product $product): void;

    public function remove(int $id): void;

    /**
     * @param Product[] $products
     * @return ProductOutputDTO[]
     */
    public function list(array $products, string $unit): array;
}
