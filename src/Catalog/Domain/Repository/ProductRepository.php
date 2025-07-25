<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Repository;

use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Price;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function find(?Category $category = null, ?Price $maxPrice = null, int $limit = 5): array;
}
