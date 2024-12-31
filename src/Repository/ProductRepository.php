<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\FilterProductDTO;
use App\Entity\Product;
use App\Utils\WeightConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private WeightConverter $weightConverter)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findByFilters(string $type, FilterProductDTO $filterProductDTO): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.type = :type')
            ->setParameter('type', $type);

        if (null !== $filterProductDTO->minQuantity) {
            $minGrams = $this->weightConverter->convertToGrams(
                $filterProductDTO->minQuantity,
                $filterProductDTO->unit
            );
            $qb->andWhere('p.quantity >= :minQuantity')
                ->setParameter('minQuantity', $minGrams);
        }

        if (null !== $filterProductDTO->maxQuantity) {
            $maxGrams = $this->weightConverter->convertToGrams(
                $filterProductDTO->maxQuantity,
                $filterProductDTO->unit
            );
            $qb->andWhere('p.quantity <= :maxQuantity')
                ->setParameter('maxQuantity', $maxGrams);
        }

        return $qb->getQuery()->getResult();
    }
}