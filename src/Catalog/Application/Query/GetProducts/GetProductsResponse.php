<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\GetProducts;

final readonly class GetProductsResponse
{
    /**
     * @param array<int, array<string, mixed>> $products
     */
    public function __construct(
        public array $products,
    ) {
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function toArray(): array
    {
        return [
            'products' => $this->products,
        ];
    }
}
