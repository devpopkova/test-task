<?php

declare(strict_types=1);

namespace App\Collection;

use App\DTO\ProductOutputDTO;
use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use App\Interfaces\Collection\ProductCollectionInterface;
use App\Interfaces\Transformer\ProductInputDTOToProductTransformerInterface;
use App\Interfaces\Transformer\ProductToOutputDTOTransformerInterface;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractProductCollection implements ProductCollectionInterface
{
    public function __construct(
        protected ProductRepository $repository,
        protected EntityManagerInterface $entityManager,
        protected ProductInputDTOToProductTransformerInterface $productInputDTOToProductTransformer,
        protected ProductToOutputDTOTransformerInterface $productToOutputDTOTransformer,
    ) {
    }

    public function add(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function remove(int $id): void
    {
        $product = $this->repository->find($id);

        if (!$product || $product->getType() !== $this->getType()) {
            throw new ProductNotFoundException();
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    abstract protected function getType(): string;

    /**
     * @param Product[] $products
     * @return ProductOutputDTO[]
     */
    public function list(array $products, string $unit): array
    {
        $transformedProducts = [];
        foreach ($products as $product) {
            $transformedProducts[] = $this->productToOutputDTOTransformer->transform($product, $unit);
        }

        return $transformedProducts;
    }
}
