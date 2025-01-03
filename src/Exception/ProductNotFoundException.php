<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class ProductNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Product not found');
    }
}
