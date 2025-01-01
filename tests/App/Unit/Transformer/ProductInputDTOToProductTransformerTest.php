<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Transformer;

use App\DTO\ProductInputDTO;
use App\Entity\Product;
use App\Enum\UnitType;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Transformer\ProductInputDTOToProductTransformer;
use App\Utils\WeightConverter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductInputDTOToProductTransformerTest extends TestCase
{
    private ProductFactoryInterface&MockObject $productFactory;
    private WeightConverter&MockObject $weightConverter;
    private ProductInputDTOToProductTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productFactory = $this->createMock(ProductFactoryInterface::class);
        $this->weightConverter = $this->createMock(WeightConverter::class);

        $this->transformer = new ProductInputDTOToProductTransformer(
            productFactory: $this->productFactory,
            weightConverter: $this->weightConverter
        );
    }

    public function testTransform(): void
    {
        $productDto = new ProductInputDTO(
            name: 'Apple',
            quantity: 2.5,
            unit: 'kg',
            type: 'FRUIT'
        );

        $expectedQuantityInGrams = 2500.0;
        $expectedProduct = $this->createMock(Product::class);

        $expectedArray = [
            "type" => 'fruit',
            "quantity" => $expectedQuantityInGrams,
            "name" => 'Apple',
            "unit" => UnitType::GRAMS->value
        ];

        $this->weightConverter
            ->expects($this->once())
            ->method('convertToGrams')
            ->with(2.5, 'kg')
            ->willReturn($expectedQuantityInGrams);

        $this->productFactory
            ->expects($this->once())
            ->method('createFromArray')
            ->with($expectedArray)
            ->willReturn($expectedProduct);

        $result = $this->transformer->transform($productDto);

        $this->assertSame($expectedProduct, $result, 'The returned Product does not match the expected Product.');
    }
}
