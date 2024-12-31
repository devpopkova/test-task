<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\ProductType;
use App\Enum\UnitType;
use Symfony\Component\Validator\Constraints as Assert;

class ProductInputDTO
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotNull(message: 'Name is required')]
        #[Assert\Length(min: 2, max: 255)]
        public ?string $name,
        #[Assert\NotNull(message: 'Quantity is required')]
        #[Assert\GreaterThan(0)]
        public ?float $quantity,
        #[Assert\NotNull(message: 'Unit is required')]
        #[Assert\Choice(callback: [self::class, 'getAllowedUnitTypes'])]
        public string $unit,
        #[Assert\NotNull(message: 'Type is required')]
        #[Assert\Choice(callback: [self::class, 'getAllowedProductTypes'])]
        public string $type,
    ) {
    }

    /**
     * @return string[]
     */
    public static function getAllowedProductTypes(): array
    {
        $types = [];
        foreach (ProductType::cases() as $case) {
            $types[] = $case->value;
        }
        return $types;
    }

    /**
     * @return string[]
     */
    public static function getAllowedUnitTypes(): array
    {
        $types = [];
        foreach (UnitType::cases() as $case) {
            $types[] = $case->value;
        }
        return $types;
    }
}
