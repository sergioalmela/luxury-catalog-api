<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Testing\Doubles;

use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\Repository\ProductRepository;
use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Price;

final class ProductRepositoryFake implements ProductRepository
{
    /** @var Product[] */
    private array $products = [];

    public function add(Product $product): void
    {
        $this->products[] = $product;
    }

    public function clean(): void
    {
        $this->products = [];
    }

    /** @return Product[] */
    public function find(?Category $category = null, ?Price $maxPrice = null, int $limit = 5): array
    {
        $filtered = $this->products;

        if (null !== $category) {
            $filtered = array_filter($filtered, fn (Product $product) => $product->category()->equals($category));
        }

        if (null !== $maxPrice) {
            $filtered = array_filter($filtered, fn (Product $product) => $product->price()->value() <= $maxPrice->value());
        }

        return \array_slice(array_values($filtered), 0, $limit);
    }
}
