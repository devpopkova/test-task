<?php

declare(strict_types=1);

namespace App\DTO;

class ProductOutputDTO
{
    public function __construct(
        public string $name,
        public float $quantity,
        public string $unit,
    ) {
    }
}
