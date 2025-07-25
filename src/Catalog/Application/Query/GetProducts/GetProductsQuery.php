<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\GetProducts;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetProductsQuery implements Query
{
    public function __construct(
        public ?string $category = null,
        public ?int $priceLessThan = null,
    ) {
    }
}
