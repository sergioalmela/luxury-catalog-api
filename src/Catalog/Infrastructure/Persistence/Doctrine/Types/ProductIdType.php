<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Persistence\Doctrine\Types;

use App\Catalog\Domain\ValueObject\ProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class ProductIdType extends StringType
{
    public const string NAME = 'product_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof ProductId ? $value->value() : $value;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductId
    {
        return \is_string($value) ? ProductId::fromPrimitives($value) : null;
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
