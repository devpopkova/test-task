<?php

declare(strict_types=1);

namespace App\Tests\App\Unit\Factory;

use App\DTO\ProductOutputDTO;
use App\Factory\ProductOutputDTOFactory;
use PHPUnit\Framework\TestCase;

class ProductOutputDTOFactoryTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $factory = new ProductOutputDTOFactory();
        $inputData = [
            'name' => 'Apple',
            'quantity' => 2.5,
            'unit' => 'kilograms',
        ];

        $dto = $factory->createFromArray($inputData);

        $this->assertInstanceOf(ProductOutputDTO::class, $dto);
        $this->assertEquals('Apple', $dto->name);
        $this->assertEquals(2.5, $dto->quantity);
        $this->assertEquals('kilograms', $dto->unit);
    }
}
