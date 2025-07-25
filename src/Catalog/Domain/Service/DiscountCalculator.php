<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Service;

use App\Catalog\Domain\Entity\Product;

final class DiscountCalculator
{
    private const int BOOTS_CATEGORY_DISCOUNT = 30;
    private const int SKU_000003_DISCOUNT = 15;
    private const string SPECIAL_SKU = '000003';

    /**
     * Calculate the highest applicable discount percentage for a given product
     * - Products in "boots" category: 30% discount
     * - Product with sku "000003": 15% discount
     * - When multiple discounts apply: use the higher discount.
     */
    public function calculateFor(Product $product): int
    {
        $discounts = [];

        if ($product->category()->isBoots()) {
            $discounts[] = self::BOOTS_CATEGORY_DISCOUNT;
        }

        if (self::SPECIAL_SKU === $product->sku()->value()) {
            $discounts[] = self::SKU_000003_DISCOUNT;
        }

        return [] === $discounts ? 0 : max($discounts);
    }
}
