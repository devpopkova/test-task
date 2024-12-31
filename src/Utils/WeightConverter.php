<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enum\UnitType;

class WeightConverter
{
    public function convertToGrams(float $quantity, string $unit): float
    {
        return $unit === UnitType::KILOGRAMS->value ? $quantity * 1000 : $quantity;
    }

    public function convertFromGrams(float $quantity, string $targetUnit): float
    {
        return $targetUnit === UnitType::KILOGRAMS->value ? $quantity / 1000 : $quantity;
    }
}
