<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

class InvalidProductTypeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid product type');
    }
}
