<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Factory;

use App\DTO\ProductInputDTO;
use App\Factory\ProductInputDTOFactory;
use App\Interfaces\Factory\ProductInputDTOFactoryInterface;
use PHPUnit\Framework\TestCase;

class ProductInputDTOFactoryTest extends TestCase
{
    private ProductInputDTOFactoryInterface $factory;

    /**
     * @dataProvider createFromArrayDataProvider
     */
    public function testCreateFromArray(
        array $inputData,
        ProductInputDTO $expectedDTO
    ): void {
        $dto = $this->factory->createFromArray($inputData);

        $this->assertInstanceOf(ProductInputDTO::class, $dto, 'Returned object is not an instance of ProductInputDTO');
        $this->assertEquals($expectedDTO->name, $dto->name, 'Product name does not match');
        $this->assertEquals($expectedDTO->quantity, $dto->quantity, 'Product quantity does not match');
        $this->assertEquals($expectedDTO->unit, $dto->unit, 'Product unit does not match');
        $this->assertEquals($expectedDTO->type, $dto->type, 'Product type does not match');
    }

    public function createFromArrayDataProvider(): array
    {
        return [
            'All valid data' => [
                'inputData' => [
                    'name' => 'Apple',
                    'quantity' => 2.5,
                    'unit' => 'Kilograms',
                    'type' => 'fruit',
                ],
                'expectedDTO' => new ProductInputDTO(
                    name: 'Apple',
                    quantity: 2.5,
                    unit: 'kilograms',
                    type: 'fruit'
                ),
            ],
            'Unit in uppercase and type in mixed case' => [
                'inputData' => [
                    'name' => 'Carrot',
                    'quantity' => 500,
                    'unit' => 'GRAMS',
                    'type' => 'Vegetable',
                ],
                'expectedDTO' => new ProductInputDTO(
                    name: 'Carrot',
                    quantity: 500,
                    unit: 'grams',
                    type: 'vegetable'
                ),
            ],
            'Unit in mixed case and type in uppercase' => [
                'inputData' => [
                    'name' => 'Banana',
                    'quantity' => 1.2,
                    'unit' => 'KiLoGrams',
                    'type' => 'fruit',
                ],
                'expectedDTO' => new ProductInputDTO(
                    name: 'Banana',
                    quantity: 1.2,
                    unit: 'kilograms',
                    type: 'fruit'
                ),
            ],
            'Quantity as integer' => [
                'inputData' => [
                    'name' => 'Potato',
                    'quantity' => 1000,
                    'unit' => 'grams',
                    'type' => 'vegetable',
                ],
                'expectedDTO' => new ProductInputDTO(
                    name: 'Potato',
                    quantity: 1000,
                    unit: 'grams',
                    type: 'vegetable'
                ),
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new ProductInputDTOFactory();
    }
}
