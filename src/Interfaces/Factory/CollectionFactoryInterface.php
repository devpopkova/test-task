<?php

declare(strict_types=1);

namespace App\Interfaces\Factory;

use App\Interfaces\Collection\ProductCollectionInterface;

interface CollectionFactoryInterface
{
    public function getCollection(string $type): ProductCollectionInterface;
}
