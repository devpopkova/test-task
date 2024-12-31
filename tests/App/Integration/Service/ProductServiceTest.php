<?php


declare(strict_types=1);

namespace App\Tests\App\Integration\Service;

use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class ProductServiceTest extends KernelTestCase
{
    private ProductService $productService;
    private EntityManagerInterface $entityManager;

    public function testGetProducts(): void
    {
        $this->createProduct('Test Product', 100, 'g');

        $request = new Request([], [], [], [], [], ['QUERY_STRING' => 'unit=kg']);

        $result = $this->productService->getProducts('fruit', $request);

        $this->assertCount(1, $result);
        $this->assertEquals('Test Product', $result[0]->name);
    }

    private function createProduct(
        string $name,
        float $quantity,
        string $unit,
        string $type = 'fruit'
    ): Product {
        $product = new Product();
        $product->setName($name)
            ->setQuantity($quantity)
            ->setUnit($unit)
            ->setType($type);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function testCreateProducts(): void
    {
        $productsData = [
            ['type' => 'fruit', 'name' => 'Apple1', 'quantity' => 5, 'unit' => 'kg'],
            ['type' => 'vegetable', 'name' => 'Carrot1', 'quantity' => 2, 'unit' => 'kg']
        ];

        $this->productService->createProducts($productsData);
        $productRepo = $this->entityManager->getRepository(Product::class);

        $this->assertNotNull($productRepo->findOneBy(['name' => 'Apple1']));
        $this->assertNotNull($productRepo->findOneBy(['name' => 'Carrot1']));
    }

    public function testDeleteProduct(): void
    {
        $product = $this->createProduct('Test Product1', 100, 'g');
        $productId = $product->getId();
        $this->productService->deleteProduct('fruit', $productId);

        $deletedProduct = $this->entityManager->getRepository(Product::class)->find($productId);

        $this->assertNull($deletedProduct);
    }

    public function testGetProductsWithValidationError(): void
    {
        $request = new Request();
        $request->query->set('unit', 'invalid_unit');

        $this->expectException(InvalidArgumentException::class);
        $this->productService->getProducts('fruit', $request);
    }

    public function testCreateProductsWithValidationError(): void
    {
        $productsData = [
            ['type' => 'fruit', 'name' => '', 'quantity' => -1, 'unit' => 'invalid_unit']
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->productService->createProducts($productsData);
    }

    public function testDeleteNonexistentProduct(): void
    {
        $this->expectException(ProductNotFoundException::class);
        $this->productService->deleteProduct('fruit', 9999);
    }

    public function testGetProductsWithMinMaxQuantities(): void
    {
        $this->createProduct('Product 1', 50, 'g');
        $this->createProduct('Product 2', 200, 'g');

        $request = new Request();
        $request->query->set('minQuantity', 150);
        $request->query->set('maxQuantity', 300);
        $request->query->set('unit', 'g');

        $result = $this->productService->getProducts('fruit', $request);

        $this->assertCount(1, $result);
        $this->assertEquals('Product 2', $result[0]->name);
    }

    public function testGetProductsWithInvalidMinMaxQuantities(): void
    {
        $request = new Request();
        $request->query->set('minQuantity', -10);
        $request->query->set('maxQuantity', -45);
        $request->query->set('unit', 'g');

        $this->expectException(InvalidArgumentException::class);
        $this->productService->getProducts('fruit', $request);
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->productService = self::getContainer()->get(ProductService::class);
        $this->entityManager->createQuery('DELETE FROM App\\Entity\\Product')->execute();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        parent::tearDown();
    }
}
