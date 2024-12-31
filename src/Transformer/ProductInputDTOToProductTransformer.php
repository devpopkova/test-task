<?php

namespace App\Transformer;

use App\DTO\ProductInputDTO;
use App\Entity\Product;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Interfaces\Transformer\ProductInputDTOToProductTransformerInterface;

class ProductInputDTOToProductTransformer implements ProductInputDTOToProductTransformerInterface
{
    public function __construct(
        private ProductFactoryInterface $productFactory,
    ) {
    }

    public function transform(ProductInputDTO $productDto): Product
    {
        return $this->productFactory->createFromArray(
            [
            "type" => $productDto->type,
            "quantity" => $productDto->quantity,
            "name" => $productDto->name,
            "unit" => $productDto->unit
            ]
        );
    }
}
