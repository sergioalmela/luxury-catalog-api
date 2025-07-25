<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Persistence\Doctrine\Types;

use App\Catalog\Domain\ValueObject\Category;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class CategoryType extends StringType
{
    public const string NAME = 'product_category';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Category ? $value->value() : $value;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Category
    {
        return \is_string($value) ? Category::fromPrimitives($value) : null;
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
