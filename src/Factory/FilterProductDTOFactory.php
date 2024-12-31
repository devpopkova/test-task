<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\FilterProductDTO;
use App\Enum\UnitType;
use App\Interfaces\Factory\FilterProductDTOFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class FilterProductDTOFactory implements FilterProductDTOFactoryInterface
{
    public function createFromRequest(Request $request): FilterProductDTO
    {
        $minQuantity = $request->query->has('minQuantity')
            ? (float)$request->query->get('minQuantity')
            : null;

        $maxQuantity = $request->query->has('maxQuantity')
            ? (float)$request->query->get('maxQuantity')
            : null;

        return new FilterProductDTO(
            unit: $request->query->get('unit', UnitType::GRAMS->value),
            minQuantity: $minQuantity,
            maxQuantity: $maxQuantity
        );
    }
}
