<?php

namespace App\Transformer;

use App\DTO\ProductInputDTO;
use App\Entity\Product;
use App\Enum\UnitType;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Interfaces\Transformer\ProductInputDTOToProductTransformerInterface;
use App\Utils\WeightConverter;

class ProductInputDTOToProductTransformer implements ProductInputDTOToProductTransformerInterface
{
    public function __construct(
        private ProductFactoryInterface $productFactory,
        private WeightConverter $weightConverter,
    ) {
    }

    public function transform(ProductInputDTO $productDto): Product
    {
        $quantity = $this->weightConverter->convertToGrams($productDto->quantity, $productDto->unit);
        return $this->productFactory->createFromArray(
            data: [
                "type" => strtolower($productDto->type),
                "quantity" => $quantity,
                "name" => $productDto->name,
                "unit" => UnitType::GRAMS->value
            ]
        );
    }
}
