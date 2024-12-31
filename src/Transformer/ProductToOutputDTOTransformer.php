<?php

namespace App\Transformer;

use App\DTO\ProductOutputDTO;
use App\Entity\Product;
use App\Interfaces\Factory\ProductOutputDTOFactoryInterface;
use App\Interfaces\Transformer\ProductToOutputDTOTransformerInterface;
use App\Utils\WeightConverter;

class ProductToOutputDTOTransformer implements ProductToOutputDTOTransformerInterface
{
    public function __construct(
        private ProductOutputDTOFactoryInterface $productOutputDTOFactory,
        private WeightConverter $weightConverter,
    ) {
    }

    public function transform(Product $product, string $unit): ProductOutputDTO
    {
        return $this->productOutputDTOFactory->createFromArray(
            [
            "type" => $product->getType(),
            "name" => $product->getName(),
            "quantity" => $this->weightConverter->convertFromGrams($product->getQuantity(), $unit),
            "unit" => $unit
            ]
        );
    }
}
