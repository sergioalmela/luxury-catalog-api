<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidSKUException extends DomainException
{
    public function __construct(string $message = 'Invalid SKU provided')
    {
        parent::__construct($message);
    }
}
