<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\ProductInputDTO;
use App\Interfaces\Factory\ProductInputDTOFactoryInterface;

class ProductInputDTOFactory implements ProductInputDTOFactoryInterface
{
    /**
     * @param array{
     *     name: ?string,
     *     quantity: ?float,
     *     unit: ?string,
     *     type: ?string
     * } $data
     */
    public function createFromArray(array $data): ProductInputDTO
    {
        return new ProductInputDTO(
            name: $data['name'] ?? null,
            quantity: $data['quantity'] ?? null,
            unit: strtolower($data['unit'] ?? ''),
            type: strtolower($data['type'] ?? '')
        );
    }
}
