<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Persistence\Doctrine\Types;

use App\Catalog\Domain\ValueObject\SKU;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class SKUType extends StringType
{
    public const string NAME = 'product_sku';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof SKU ? $value->value() : $value;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?SKU
    {
        return \is_string($value) ? SKU::fromPrimitives($value) : null;
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
