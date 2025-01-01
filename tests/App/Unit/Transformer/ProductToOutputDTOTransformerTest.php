<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Transformer;

use App\DTO\ProductOutputDTO;
use App\Entity\Product;
use App\Interfaces\Factory\ProductOutputDTOFactoryInterface;
use App\Transformer\ProductToOutputDTOTransformer;
use App\Utils\WeightConverter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductToOutputDTOTransformerTest extends TestCase
{
    private ProductOutputDTOFactoryInterface&MockObject $productOutputDTOFactory;
    private WeightConverter&MockObject $weightConverter;
    private ProductToOutputDTOTransformer $transformer;

    public function testTransform(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('getType')->willReturn('fruit');
        $product->method('getName')->willReturn('Apple');
        $product->method('getQuantity')->willReturn(1500.0);

        $unit = 'kg';
        $convertedQuantity = 1.5;

        $expectedOutputDTO = new ProductOutputDTO(
            name: 'Apple',
            quantity: $convertedQuantity,
            unit: $unit,
        );

        $expectedArray = [
            "type" => 'fruit',
            "name" => 'Apple',
            "quantity" => $convertedQuantity,
            "unit" => $unit,
        ];

        $this->weightConverter
            ->expects($this->once())
            ->method('convertFromGrams')
            ->with(1500.0, $unit)
            ->willReturn($convertedQuantity);

        $this->productOutputDTOFactory
            ->expects($this->once())
            ->method('createFromArray')
            ->with($expectedArray)
            ->willReturn($expectedOutputDTO);

        $result = $this->transformer->transform($product, $unit);

        $this->assertEquals(
            $expectedOutputDTO,
            $result,
            'The returned ProductOutputDTO does not match the expected one.'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->productOutputDTOFactory = $this->createMock(ProductOutputDTOFactoryInterface::class);
        $this->weightConverter = $this->createMock(WeightConverter::class);

        $this->transformer = new ProductToOutputDTOTransformer(
            $this->productOutputDTOFactory,
            $this->weightConverter
        );
    }
}
