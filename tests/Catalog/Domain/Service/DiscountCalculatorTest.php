<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\Service;

use App\Catalog\Domain\Service\DiscountCalculator;
use App\Tests\Catalog\Infrastructure\Testing\Builders\ProductBuilder;
use PHPUnit\Framework\TestCase;

final class DiscountCalculatorTest extends TestCase
{
    private DiscountCalculator $discountCalculator;

    protected function setUp(): void
    {
        $this->discountCalculator = new DiscountCalculator();
    }

    public function testCalculateDiscountForBootsCategoryProduct(): void
    {
        $product = ProductBuilder::create()
            ->withSKU('000001')
            ->withBootsCategory()
            ->withPrice(89000)
            ->build();

        $discountPercentage = $this->discountCalculator->calculateFor($product);

        self::assertSame(30, $discountPercentage);
    }

    public function testCalculateDiscountForSpecialSKU(): void
    {
        $product = ProductBuilder::create()
            ->withSpecialSKU()
            ->withSandalsCategory()
            ->withPrice(71000)
            ->build();

        $discountPercentage = $this->discountCalculator->calculateFor($product);

        self::assertSame(15, $discountPercentage);
    }

    public function testCalculateDiscountWhenMultipleDiscountsApplyReturnsHighest(): void
    {
        $product = ProductBuilder::create()
            ->withSpecialSKU()
            ->withBootsCategory()
            ->withPrice(71000)
            ->build();

        $discountPercentage = $this->discountCalculator->calculateFor($product);

        self::assertSame(30, $discountPercentage);
    }

    public function testCalculateDiscountForProductWithNoEligibility(): void
    {
        $product = ProductBuilder::create()
            ->withSKU('000002')
            ->withSneakersCategory()
            ->withPrice(59000)
            ->build();

        $discountPercentage = $this->discountCalculator->calculateFor($product);

        self::assertSame(0, $discountPercentage);
    }
}
