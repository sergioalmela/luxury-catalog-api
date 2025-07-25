<?php

declare(strict_types=1);

namespace App\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidSKUException;
use Stringable;

final readonly class SKU implements Stringable
{
    private function __construct(private string $value)
    {
    }

    public static function of(string $value): self
    {
        if ('' === mb_trim($value)) {
            throw new InvalidSKUException('SKU cannot be empty');
        }

        if (mb_strlen($value) > 50) {
            throw new InvalidSKUException('SKU cannot exceed 50 characters');
        }

        return new self($value);
    }

    public static function fromPrimitives(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $sku): bool
    {
        return $this->value === $sku->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
