<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\UnitType;
use Symfony\Component\Validator\Constraints as Assert;

class FilterProductDTO
{
    public function __construct(
        #[Assert\Choice(callback: [self::class, 'getAllowedUnitTypes'])]
        public string $unit,
        #[Assert\Positive(message: 'minQuantity must be > 0')]
        public ?float $minQuantity = null,
        #[Assert\Positive(message: 'maxQuantity must be > 0')]
        #[Assert\GreaterThanOrEqual(
            propertyPath: 'minQuantity',
            message: 'maxQuantity must be greater or equal than minQuantity'
        )]
        public ?float $maxQuantity = null,
    ) {
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
