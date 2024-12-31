<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\ProductOutputDTO;
use App\Interfaces\Factory\ProductOutputDTOFactoryInterface;

class ProductOutputDTOFactory implements ProductOutputDTOFactoryInterface
{
    /**
     * @param array{
     *     name: string,
     *     quantity: float,
     *     unit: string
     * } $data
     */
    public function createFromArray(array $data): ProductOutputDTO
    {
        return new ProductOutputDTO(
            name: $data['name'],
            quantity: $data['quantity'],
            unit: $data['unit']
        );
    }
}
