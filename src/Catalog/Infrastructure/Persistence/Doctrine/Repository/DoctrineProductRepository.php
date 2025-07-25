<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Persistence\Doctrine\Repository;

use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\Repository\ProductRepository;
use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Price;
use App\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;
use Doctrine\ORM\QueryBuilder;

final class DoctrineProductRepository extends DoctrineRepository implements ProductRepository
{
    public function find(?Category $category = null, ?Price $maxPrice = null, int $limit = 5): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if ($category instanceof Category) {
            $queryBuilder->andWhere('p.category = :category')
               ->setParameter('category', $category);
        }

        if ($maxPrice instanceof Price) {
            $queryBuilder->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        $queryBuilder->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    private function createQueryBuilder(string $alias): QueryBuilder
    {
        return $this->repository(Product::class)->createQueryBuilder($alias);
    }
}
