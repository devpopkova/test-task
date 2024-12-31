<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Utils;

use App\Enum\UnitType;
use App\Utils\WeightConverter;
use PHPUnit\Framework\TestCase;

class WeightConverterTest extends TestCase
{
    private WeightConverter $weightConverter;

    /**
     * @dataProvider provideConvertToGramsData
     */
    public function testConvertToGrams(float $quantity, string $unit, float $expected): void
    {
        $result = $this->weightConverter->convertToGrams($quantity, $unit);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider provideConvertFromGramsData
     */
    public function testConvertFromGrams(float $quantity, string $targetUnit, float $expected): void
    {
        $result = $this->weightConverter->convertFromGrams($quantity, $targetUnit);

        $this->assertEquals($expected, $result);
    }

    public function provideConvertToGramsData(): array
    {
        return [
            'converts kilograms to grams' => [
                'quantity' => 1.5,
                'unit' => UnitType::KILOGRAMS->value,
                'expected' => 1500.0,
            ],
            'keeps grams as is' => [
                'quantity' => 1500.0,
                'unit' => UnitType::GRAMS->value,
                'expected' => 1500.0,
            ],
        ];
    }

    public function provideConvertFromGramsData(): array
    {
        return [
            'converts grams to kilograms' => [
                'quantity' => 1500.0,
                'targetUnit' => UnitType::KILOGRAMS->value,
                'expected' => 1.5,
            ],
            'keeps grams as is' => [
                'quantity' => 1500.0,
                'targetUnit' => UnitType::GRAMS->value,
                'expected' => 1500.0,
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->weightConverter = new WeightConverter();
    }
}
