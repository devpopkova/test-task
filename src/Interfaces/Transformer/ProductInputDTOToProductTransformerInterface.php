<?php

namespace App\Interfaces\Transformer;

use App\DTO\ProductInputDTO;
use App\Entity\Product;

interface ProductInputDTOToProductTransformerInterface
{
    public function transform(ProductInputDTO $productDto): Product;
}
