<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Factory;

use App\Entity\Product;
use App\Factory\ProductFactory;
use App\Interfaces\Factory\ProductFactoryInterface;
use App\Enum\UnitType;
use PHPUnit\Framework\TestCase;
use TypeError;

class ProductFactoryTest extends TestCase
{
    private ProductFactoryInterface $productFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productFactory = new ProductFactory();
    }
    /**
     * @dataProvider createFromArrayDataProvider
     */
    public function testCreateFromArray(array $inputData, Product $expectedProduct): void
    {
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
            'All valid data' => [
                'inputData' => [
                    'name' => 'Apple',
                    'type' => 'fruit',
                    'quantity' => 1000,
                    'unit' => UnitType::GRAMS->value,
                ],
                'expectedProduct' => (new Product())
                    ->setName('Apple')
                    ->setType('fruit')
                    ->setQuantity(1000.0)
                    ->setUnit(UnitType::GRAMS->value),
            ],
            'Another valid product' => [
                'inputData' => [
                    'name' => 'Carrot',
                    'type' => 'vegetable',
                    'quantity' => 500,
                    'unit' => UnitType::GRAMS->value,
                ],
                'expectedProduct' => (new Product())
                    ->setName('Carrot')
                    ->setType('vegetable')
                    ->setQuantity(500.0)
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
}
