<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidCategoryException extends DomainException
{
    public function __construct(string $message = 'Invalid category provided')
    {
        parent::__construct($message);
    }
}
