<?php

declare(strict_types=1);

namespace App\Interfaces\Service;

interface StorageServiceInterface
{
    public function initializeFromJson(string $jsonContent): void;
}
