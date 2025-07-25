<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Persistence\Doctrine\Types;

use App\Catalog\Domain\ValueObject\Price;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Override;

final class ProductPriceType extends IntegerType
{
    public const string NAME = 'product_price';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        return $value instanceof Price ? $value->value() : $value;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Price
    {
        return is_numeric($value) ? Price::fromPrimitives((int) $value) : null;
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
