<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Persistence\Doctrine\Types;

use App\Catalog\Domain\ValueObject\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class NameType extends StringType
{
    public const string NAME = 'product_name';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Name ? $value->value() : $value;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Name
    {
        return \is_string($value) ? Name::fromPrimitives($value) : null;
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
