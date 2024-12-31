<?php

declare(strict_types=1);

namespace App\Interfaces\Factory;

use App\DTO\FilterProductDTO;
use Symfony\Component\HttpFoundation\Request;

interface FilterProductDTOFactoryInterface
{
    public function createFromRequest(Request $request): FilterProductDTO;
}
