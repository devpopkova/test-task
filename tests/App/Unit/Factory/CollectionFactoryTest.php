<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Factory;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Exception\ProductNotFoundException;
use App\Factory\CollectionFactory;
use PHPUnit\Framework\TestCase;

class CollectionFactoryTest extends TestCase
{
    private FruitCollection $fruitCollection;

    private VegetableCollection $vegetableCollection;

    private CollectionFactory $collectionFactory;

    public function testGetCollectionReturnsFruitCollection(): void
    {
        $type = 'fruit';
        $result = $this->collectionFactory->getCollection($type);
        $this->assertInstanceOf(FruitCollection::class, $result);
        $this->assertSame($this->fruitCollection, $result);
    }

    public function testGetCollectionReturnsVegetableCollection(): void
    {
        $type = 'vegetable';
        $result = $this->collectionFactory->getCollection($type);
        $this->assertInstanceOf(VegetableCollection::class, $result);
        $this->assertSame($this->vegetableCollection, $result);
    }

    public function testGetCollectionThrowsExceptionForUnknownType(): void
    {
        $type = 'meat';
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionMessage('Product not found');
        $this->collectionFactory->getCollection($type);
    }

    public function testGetCollectionIsCaseInsensitive(): void
    {
        $type = 'FrUiT';
        $result = $this->collectionFactory->getCollection($type);
        $this->assertInstanceOf(FruitCollection::class, $result);
        $this->assertSame($this->fruitCollection, $result);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->fruitCollection = $this->createMock(FruitCollection::class);
        $this->vegetableCollection = $this->createMock(VegetableCollection::class);
        $this->collectionFactory = new CollectionFactory(
            $this->fruitCollection,
            $this->vegetableCollection
        );
    }
}
