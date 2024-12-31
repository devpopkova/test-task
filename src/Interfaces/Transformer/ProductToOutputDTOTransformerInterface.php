<?php

namespace App\Interfaces\Transformer;

use App\DTO\ProductOutputDTO;
use App\Entity\Product;

interface ProductToOutputDTOTransformerInterface
{
    public function transform(Product $product, string $unit): ProductOutputDTO;
}
