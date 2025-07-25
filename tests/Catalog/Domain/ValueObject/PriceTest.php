<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidPriceException;
use App\Catalog\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;

final class PriceTest extends TestCase
{
    public function testCreateValidPrice(): void
    {
        $price = Price::of(89000);

        self::assertSame(89000, $price->value());
        self::assertSame(890.0, $price->toEuros());
        self::assertSame('89000', (string) $price);
    }

    public function testCreatePriceWithNegativeValueThrowsException(): void
    {
        $this->expectException(InvalidPriceException::class);
        $this->expectExceptionMessage('Price cannot be negative');

        Price::of(-1);
    }

    public function testApplyDiscountPercentage(): void
    {
        $price = Price::of(89000);

        $discountedPrice = $price->applyDiscount(30);

        self::assertSame(62300, $discountedPrice->value());
    }

    public function testApplyZeroDiscount(): void
    {
        $price = Price::of(89000);

        $discountedPrice = $price->applyDiscount(0);

        self::assertSame(89000, $discountedPrice->value());
    }

    public function testApplyFullDiscount(): void
    {
        $price = Price::of(89000);

        $discountedPrice = $price->applyDiscount(100);

        self::assertSame(0, $discountedPrice->value());
    }

    public function testApplyInvalidDiscountPercentageThrowsException(): void
    {
        $price = Price::of(89000);

        $this->expectException(InvalidPriceException::class);
        $this->expectExceptionMessage('Discount percentage must be between 0 and 100');

        $price->applyDiscount(-1);
    }

    public function testApplyTooHighDiscountPercentageThrowsException(): void
    {
        $price = Price::of(89000);

        $this->expectException(InvalidPriceException::class);
        $this->expectExceptionMessage('Discount percentage must be between 0 and 100');

        $price->applyDiscount(101);
    }

    public function testEqualsPrices(): void
    {
        $price1 = Price::of(89000);
        $price2 = Price::of(89000);
        $price3 = Price::of(99000);

        self::assertTrue($price1->equals($price2));
        self::assertFalse($price1->equals($price3));
    }

    public function testFromPrimitives(): void
    {
        $price = Price::fromPrimitives(89000);

        self::assertSame(89000, $price->value());
    }
}
