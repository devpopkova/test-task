<?php

declare(strict_types=1);

namespace App\Interfaces\Service;

use App\DTO\ProductOutputDTO;
use Symfony\Component\HttpFoundation\Request;

interface ProductServiceInterface
{
    /**
     * @param string $type
     * @param Request $request
     * @return ProductOutputDTO[]
     */
    public function getProducts(string $type, Request $request): array;

    /**
     * @param array<array{
     *     type: string,
     *     name: string,
     *     quantity: float,
     *     unit: string
     * }> $productsData
     */
    public function createProducts(array $productsData): void;

    public function deleteProduct(string $type, int $id): void;
}
