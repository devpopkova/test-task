<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Transformer;

use App\DTO\ProductInputDTO;
use App\Entity\Product;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Transformer\ProductInputDTOToProductTransformer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductInputDTOToProductTransformerTest extends TestCase
{
    private ProductFactoryInterface&MockObject $productFactory;
    private ProductInputDTOToProductTransformer $transformer;

    public function testTransform(): void
    {
        $productDto = new ProductInputDTO(
            name: 'Apple',
            quantity: 2.5,
            unit: 'kg',
            type: 'FRUIT'
        );

        $expectedProduct = $this->createMock(Product::class);

        $expectedArray = [
            "type" => 'FRUIT',
            "quantity" => 2.5,
            "name" => 'Apple',
            "unit" => 'kg'
        ];

        $this->productFactory
            ->expects($this->once())
            ->method('createFromArray')
            ->with($expectedArray)
            ->willReturn($expectedProduct);

        $result = $this->transformer->transform($productDto);

        $this->assertSame($expectedProduct, $result, 'The returned Product does not match the expected Product.');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->productFactory = $this->createMock(ProductFactoryInterface::class);

        $this->transformer = new ProductInputDTOToProductTransformer($this->productFactory);
    }
}
