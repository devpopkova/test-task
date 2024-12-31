<?php

namespace App\Tests\App\Integration\Collection;

use App\Collection\AbstractProductCollection;
use App\DTO\FilterProductDTO;
use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use App\Interfaces\Transformer\ProductInputDTOToProductTransformerInterface;
use App\Interfaces\Transformer\ProductToOutputDTOTransformerInterface;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractProductCollectionTest extends KernelTestCase
{
    private AbstractProductCollection $collection;
    private ProductRepository $repository;
    private EntityManagerInterface $entityManager;
    private ProductInputDTOToProductTransformerInterface $inputTransformer;
    private ProductToOutputDTOTransformerInterface $outputTransformer;

    public function testAdd(): void
    {
        $this->createProduct('Test Product', 10, 'kg');

        $persistedProduct = $this->repository->findOneBy(['name' => 'Test Product']);
        $this->assertNotNull($persistedProduct);
        $this->assertEquals('Test Product', $persistedProduct->getName());
    }

    private function createProduct(
        string $name,
        float $quantity,
        string $unit,
        string $type = 'vegetable'
    ): Product {
        $product = new Product();
        $quantity = $unit === 'kg' ? $quantity * 1000 : $quantity;
        $product->setName($name)
            ->setQuantity($quantity)
            ->setUnit($unit)
            ->setType($type);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function testRemove(): void
    {
        $product = $this->createProduct('Test Product', 10, 'kg');
        $productId = $product->getId();
        $this->collection->remove($productId);

        $this->assertNull($this->repository->find($productId));
    }

    public function testRemoveThrowsException(): void
    {
        $this->expectException(ProductNotFoundException::class);
        $this->collection->remove(9999);
    }

    public function testList(): void
    {
        $this->createProduct('Test Product', '10', 'kg', 'vegetable');

        $filterDTO = new FilterProductDTO(
            unit: 'g',
            minQuantity: 5000,
            maxQuantity: 15000
        );

        $products = $this->repository->findByFilters('vegetable', $filterDTO);
        $results = $this->collection->list($products, $filterDTO->unit);

        $this->assertCount(1, $results);
        $this->assertEquals('Test Product', $results[0]->name);
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->repository = static::getContainer()->get(ProductRepository::class);
        $this->inputTransformer = static::getContainer()->get(ProductInputDTOToProductTransformerInterface::class);
        $this->outputTransformer = static::getContainer()->get(ProductToOutputDTOTransformerInterface::class);

        $this->collection = new class(
            $this->repository,
            $this->entityManager,
            $this->inputTransformer,
            $this->outputTransformer
        ) extends AbstractProductCollection {
            protected function getType(): string
            {
                return 'vegetable';
            }
        };

        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        $this->entityManager->close();
        parent::tearDown();
    }
}
