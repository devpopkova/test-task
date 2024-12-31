<?php

declare(strict_types=1);

namespace App\Tests\App\Integration\Service;

use App\Entity\Product;
use App\Service\StorageService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StorageServiceTest extends KernelTestCase
{
    private StorageService $storageService;
    private EntityManagerInterface $entityManager;

    public function testInitializeFromJsonWithValidData(): void
    {
        $jsonContent = json_encode(
            [
                [
                    'type' => 'fruit',
                    'name' => 'Apple',
                    'quantity' => 1.5,
                    'unit' => 'kg'
                ],
                [
                    'type' => 'vegetable',
                    'name' => 'Carrot',
                    'quantity' => 2.0,
                    'unit' => 'kg'
                ]
            ]
        );

        $this->storageService->initializeFromJson($jsonContent);

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $this->assertCount(2, $products);
        $this->assertEquals('Apple', $products[0]->getName());
        $this->assertEquals('Carrot', $products[1]->getName());
    }

    public function testInitializeFromJsonWithInvalidJson(): void
    {
        $invalidJsonContent = '{invalid_json}';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON format');

        $this->storageService->initializeFromJson($invalidJsonContent);
    }

    public function testInitializeFromJsonWithValidationErrors(): void
    {
        $jsonContent = json_encode(
            [
                [
                    'type' => 'fruit',
                    'name' => '',
                    'quantity' => -1,
                    'unit' => 'invalid_unit'
                ]
            ]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            '{"name":["This value is too short. It should have 2 characters or more."],"quantity":["This value should be greater than 0."],"unit":["The value you selected is not a valid choice."]}'
        );

        $this->storageService->initializeFromJson($jsonContent);
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->storageService = self::getContainer()->get(StorageService::class);
    }
}
