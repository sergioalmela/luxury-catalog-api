<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Testing\Builders;

use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Name;
use App\Catalog\Domain\ValueObject\Price;
use App\Catalog\Domain\ValueObject\SKU;

final class ProductBuilder
{
    private string $sku = '000001';
    private string $name = 'Test Product';
    private string $category = 'boots';
    private int $price = 89000;

    public static function create(): self
    {
        return new self();
    }

    public function withSKU(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function withPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function withBootsCategory(): self
    {
        $this->category = 'boots';

        return $this;
    }

    public function withSandalsCategory(): self
    {
        $this->category = 'sandals';

        return $this;
    }

    public function withSneakersCategory(): self
    {
        $this->category = 'sneakers';

        return $this;
    }

    public function withSpecialSKU(): self
    {
        $this->sku = '000003';

        return $this;
    }

    public function build(): Product
    {
        return Product::create(
            SKU::of($this->sku),
            Name::of($this->name),
            Category::of($this->category),
            Price::of($this->price)
        );
    }
}
