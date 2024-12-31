<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Factory;

use App\Entity\Product;
use App\Enum\UnitType;
use App\Factory\ProductFactory;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Utils\WeightConverter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TypeError;

class ProductFactoryTest extends TestCase
{
    /**
     *
     *
     * @var WeightConverter&MockObject
     */
    private WeightConverter $weightConverter;

    private ProductFactoryInterface $productFactory;

    /**
     * @dataProvider createFromArrayDataProvider
     */
    public function testCreateFromArray(
        array $inputData,
        float $convertedQuantity,
        Product $expectedProduct
    ): void {
        $this->weightConverter
            ->expects($this->once())
            ->method('convertToGrams')
            ->with($inputData['quantity'], $inputData['unit'])
            ->willReturn($convertedQuantity);

        $product = $this->productFactory->createFromArray($inputData);

        $this->assertInstanceOf(Product::class, $product, 'Returned object is not an instance of Product');
        $this->assertEquals($expectedProduct->getName(), $product->getName(), 'Product name does not match');
        $this->assertEquals($expectedProduct->getType(), $product->getType(), 'Product type does not match');
        $this->assertEquals(
            $expectedProduct->getQuantity(),
            $product->getQuantity(),
            'Product quantity does not match'
        );
        $this->assertEquals($expectedProduct->getUnit(), $product->getUnit(), 'Product unit does not match');
    }

    public function createFromArrayDataProvider(): array
    {
        return [
            'All valid data with kilograms' => [
                'inputData' => [
                    'name' => 'Apple',
                    'type' => 'fruit',
                    'quantity' => 2,
                    'unit' => UnitType::KILOGRAMS->value,
                ],
                'convertedQuantity' => 2000.0,
                'expectedProduct' => (new Product())
                    ->setName('Apple')
                    ->setType('fruit')
                    ->setQuantity(2000.0)
                    ->setUnit(UnitType::GRAMS->value),
            ],
            'All valid data with grams' => [
                'inputData' => [
                    'name' => 'Carrot',
                    'type' => 'vegetable',
                    'quantity' => 500,
                    'unit' => UnitType::GRAMS->value,
                ],
                'convertedQuantity' => 500.0,
                'expectedProduct' => (new Product())
                    ->setName('Carrot')
                    ->setType('vegetable')
                    ->setQuantity(500.0)
                    ->setUnit(UnitType::GRAMS->value),
            ],
            'Negative quantity' => [
                'inputData' => [
                    'name' => 'Negative Stock',
                    'type' => 'fruit',
                    'quantity' => -5, // could be before validation.
                    'unit' => UnitType::GRAMS->value,
                ],
                'convertedQuantity' => -5.0,
                'expectedProduct' => (new Product())
                    ->setName('Negative Stock')
                    ->setType('fruit')
                    ->setQuantity(-5.0)
                    ->setUnit(UnitType::GRAMS->value),
            ],
            'Mixed case type and unit' => [
                'inputData' => [
                    'name' => 'Banana',
                    'type' => 'FrUiT',
                    'quantity' => 3,
                    'unit' => 'KiLoGrams',
                ],
                'convertedQuantity' => 3000.0,
                'expectedProduct' => (new Product())
                    ->setName('Banana')
                    ->setType('fruit')
                    ->setQuantity(3000.0)
                    ->setUnit(UnitType::GRAMS->value),
            ],
        ];
    }

    public function testCreateFromArrayMissingFields(): void
    {
        $inputData = [
            'type' => 'fruit',
            'quantity' => 1,
            'unit' => UnitType::GRAMS->value,
        ];
        $this->expectException(TypeError::class);
        $this->productFactory->createFromArray($inputData);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->weightConverter = $this->createMock(WeightConverter::class);
        $this->productFactory = new ProductFactory($this->weightConverter);
    }
}
