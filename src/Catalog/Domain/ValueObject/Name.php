<?php

declare(strict_types=1);

namespace App\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidNameException;
use Stringable;

final readonly class Name implements Stringable
{
    private function __construct(private string $value)
    {
    }

    public static function of(string $value): self
    {
        if ('' === mb_trim($value)) {
            throw new InvalidNameException('Name cannot be empty');
        }

        if (mb_strlen($value) > 255) {
            throw new InvalidNameException('Name cannot exceed 255 characters');
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

    public function equals(self $name): bool
    {
        return $this->value === $name->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
