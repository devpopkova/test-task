<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Factory;

use App\DTO\FilterProductDTO;
use App\Enum\UnitType;
use App\Factory\FilterProductDTOFactory;
use App\Interfaces\Factory\FilterProductDTOFactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FilterProductDTOFactoryTest extends TestCase
{
    private FilterProductDTOFactoryInterface $factory;

    /**
     * @dataProvider requestDataProvider
     */
    public function testCreateFromRequestVariousScenarios(array $queryParams, FilterProductDTO $expectedDto): void
    {
        $request = new Request(query: $queryParams);

        $dto = $this->factory->createFromRequest($request);

        $this->assertInstanceOf(
            FilterProductDTO::class,
            $dto,
            'Returned object is not an instance of FilterProductDTO'
        );
        $this->assertEquals($expectedDto->unit, $dto->unit, 'Unit does not match expected value');
        $this->assertEquals($expectedDto->minQuantity, $dto->minQuantity, 'MinQuantity does not match expected value');
        $this->assertEquals($expectedDto->maxQuantity, $dto->maxQuantity, 'MaxQuantity does not match expected value');
    }

    public function requestDataProvider(): array
    {
        return [
            'All parameters provided' => [
                'queryParams' => [
                    'unit' => UnitType::KILOGRAMS->value,
                    'minQuantity' => '10',
                    'maxQuantity' => '100',
                ],
                'expectedDto' => new FilterProductDTO(
                    unit: UnitType::KILOGRAMS->value,
                    minQuantity: 10,
                    maxQuantity: 100
                ),
            ],
            'Missing minQuantity' => [
                'queryParams' => [
                    'unit' => UnitType::GRAMS->value,
                    'maxQuantity' => '50',
                ],
                'expectedDto' => new FilterProductDTO(
                    unit: UnitType::GRAMS->value,
                    minQuantity: null,
                    maxQuantity: 50
                ),
            ],
            'No parameters' => [
                'queryParams' => [
                ],
                'expectedDto' => new FilterProductDTO(
                    unit: UnitType::GRAMS->value,
                    minQuantity: null,
                    maxQuantity: null
                ),
            ],
            'Empty unit string' => [
                'queryParams' => [
                    'unit' => '',
                    'minQuantity' => 20,
                    'maxQuantity' => 200,
                ],
                'expectedDto' => new FilterProductDTO(
                    unit: '',
                    minQuantity: 20,
                    maxQuantity: 200
                ),
            ],
            'Mixed case unit string' => [
                'queryParams' => [
                    'unit' => 'KiLoGrams',
                    'minQuantity' => '25',
                    'maxQuantity' => '250',
                ],
                'expectedDto' => new FilterProductDTO(
                    unit: 'KiLoGrams',
                    minQuantity: 25,
                    maxQuantity: 250
                ),
            ],
            'Float quantities' => [
                'queryParams' => [
                    'unit' => UnitType::GRAMS->value,
                    'minQuantity' => '10.5',
                    'maxQuantity' => '100.5',
                ],
                'expectedDto' => new FilterProductDTO(
                    unit: UnitType::GRAMS->value,
                    minQuantity: 10.5,
                    maxQuantity: 100.5
                ),
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new FilterProductDTOFactory();
    }
}
