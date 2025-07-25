<?php

declare(strict_types=1);

namespace App\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidCategoryException;
use Stringable;

final readonly class Category implements Stringable
{
    private function __construct(private string $value)
    {
    }

    public static function of(string $value): self
    {
        if ('' === mb_trim($value)) {
            throw new InvalidCategoryException('Category cannot be empty');
        }

        if (mb_strlen($value) > 100) {
            throw new InvalidCategoryException('Category cannot exceed 100 characters');
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

    public function equals(self $category): bool
    {
        return $this->value === $category->value;
    }

    public function isBoots(): bool
    {
        return 'boots' === $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
