<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Entity;

use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Name;
use App\Catalog\Domain\ValueObject\Price;
use App\Catalog\Domain\ValueObject\ProductId;
use App\Catalog\Domain\ValueObject\SKU;

final readonly class Product
{
    private function __construct(
        private ProductId $productId,
        private SKU $sku,
        private Name $name,
        private Category $category,
        private Price $price,
    ) {
    }

    public static function create(
        SKU $sku,
        Name $name,
        Category $category,
        Price $price,
    ): self {
        return new self(
            ProductId::generate(),
            $sku,
            $name,
            $category,
            $price
        );
    }

    public static function fromPrimitives(
        string $id,
        string $sku,
        string $name,
        string $category,
        int $price,
    ): self {
        return new self(
            ProductId::fromPrimitives($id),
            SKU::fromPrimitives($sku),
            Name::fromPrimitives($name),
            Category::fromPrimitives($category),
            Price::fromPrimitives($price)
        );
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function sku(): SKU
    {
        return $this->sku;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->productId->value(),
            'sku' => $this->sku->value(),
            'name' => $this->name->value(),
            'category' => $this->category->value(),
            'price' => $this->price->value(),
        ];
    }
}
