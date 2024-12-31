<?php


declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\DTO\FilterProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    private ProductRepository $repository;

    private EntityManagerInterface $entityManager;

    public function testFindByFiltersInGrams(): void
    {
        $this->createProduct('Strawberry', 200, 'fruit');
        $this->createProduct('Blueberry', 700, 'fruit');
        $this->createProduct('Raspberry', 500, 'fruit');

        $filter = new FilterProductDTO(
            unit: 'g',
            minQuantity: 300,
            maxQuantity: 600
        );

        $products = $this->repository->findByFilters('fruit', $filter);

        $this->assertCount(1, $products);
        $this->assertEquals('Raspberry', $products[0]->getName());
        $this->assertEquals(500, $products[0]->getQuantity());
    }

    private function createProduct(
        string $name,
        float $quantity,
        string $type
    ): Product {
        $product = new Product();
        $product->setName($name)
            ->setQuantity($quantity)
            ->setUnit('g')
            ->setType($type);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function testFindByFiltersInKG(): void
    {
        $this->createProduct('Apple', 500, 'fruit');
        $this->createProduct('Carrot', 300, 'vegetable');
        $this->createProduct('Banana', 1000, 'fruit');

        $filter = new FilterProductDTO(
            unit: 'kg',
            minQuantity: 0.5,
            maxQuantity: 1.0
        );

        $products = $this->repository->findByFilters('fruit', $filter);

        $this->assertCount(2, $products);
    }

    public function testFindByFiltersNoResults(): void
    {
        $this->createProduct('Orange', 200, 'fruit');

        $filter = new FilterProductDTO(
            unit: 'kg',
            minQuantity: 1.0,
            maxQuantity: 2.0
        );

        $products = $this->repository->findByFilters('fruit', $filter);

        $this->assertCount(0, $products);
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(ProductRepository::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->createQuery('DELETE FROM App\Entity\Product')->execute();
    }
}
