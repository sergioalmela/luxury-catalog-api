<?php

declare(strict_types=1);

namespace App\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidPriceException;
use Stringable;

final readonly class Price implements Stringable
{
    private function __construct(private int $value)
    {
    }

    public static function of(int $value): self
    {
        if ($value < 0) {
            throw new InvalidPriceException('Price cannot be negative');
        }

        return new self($value);
    }

    public static function fromPrimitives(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function applyDiscount(int $discountPercentage): self
    {
        if ($discountPercentage < 0 || $discountPercentage > 100) {
            throw new InvalidPriceException('Discount percentage must be between 0 and 100');
        }

        $discountAmount = ($this->value * $discountPercentage) / 100;
        $finalPrice = $this->value - (int) round($discountAmount);

        return new self($finalPrice);
    }

    public function toEuros(): float
    {
        return $this->value / 100.0;
    }

    public function equals(self $price): bool
    {
        return $this->value === $price->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
