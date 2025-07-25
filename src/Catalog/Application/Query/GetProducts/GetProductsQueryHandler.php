<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\GetProducts;

use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\Repository\ProductRepository;
use App\Catalog\Domain\Service\DiscountCalculator;
use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Price;
use App\Shared\Domain\Bus\Query\QueryHandler;

final readonly class GetProductsQueryHandler implements QueryHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private DiscountCalculator $discountCalculator,
    ) {
    }

    public function __invoke(GetProductsQuery $getProductsQuery): GetProductsResponse
    {
        $categoryFilter = null !== $getProductsQuery->category ? Category::of($getProductsQuery->category) : null;
        $maxPriceFilter = null !== $getProductsQuery->priceLessThan ? Price::of($getProductsQuery->priceLessThan) : null;

        $products = $this->productRepository->find($categoryFilter, $maxPriceFilter, 5);

        $productsResponse = array_map(
            function (Product $product): array {
                $originalPrice = $product->price();
                $discountPercentage = $this->discountCalculator->calculateFor($product);
                $finalPrice = $originalPrice->applyDiscount($discountPercentage);

                return [
                    'sku' => $product->sku()->value(),
                    'name' => $product->name()->value(),
                    'category' => $product->category()->value(),
                    'price' => [
                        'original' => $originalPrice->value(),
                        'final' => $finalPrice->value(),
                        'discount_percentage' => $discountPercentage > 0 ? $discountPercentage.'%' : null,
                        'currency' => 'EUR',
                    ],
                ];
            },
            $products
        );

        return new GetProductsResponse($productsResponse);
    }
}
