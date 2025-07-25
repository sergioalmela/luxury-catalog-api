<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidPriceException extends DomainException
{
    public function __construct(string $message = 'Invalid price provided')
    {
        parent::__construct($message);
    }
}
