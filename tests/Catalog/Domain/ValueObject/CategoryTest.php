<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidCategoryException;
use App\Catalog\Domain\ValueObject\Category;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    public function testCreateValidCategory(): void
    {
        $category = Category::of('boots');

        self::assertSame('boots', $category->value());
        self::assertSame('boots', (string) $category);
    }

    public function testCreateCategoryWithEmptyStringThrowsException(): void
    {
        $this->expectException(InvalidCategoryException::class);
        $this->expectExceptionMessage('Category cannot be empty');

        Category::of('');
    }

    public function testCreateCategoryWithWhitespaceOnlyThrowsException(): void
    {
        $this->expectException(InvalidCategoryException::class);
        $this->expectExceptionMessage('Category cannot be empty');

        Category::of('   ');
    }

    public function testCreateCategoryWithTooLongValueThrowsException(): void
    {
        $longCategory = str_repeat('a', 101);

        $this->expectException(InvalidCategoryException::class);
        $this->expectExceptionMessage('Category cannot exceed 100 characters');

        Category::of($longCategory);
    }

    public function testEqualsCategories(): void
    {
        $category1 = Category::of('boots');
        $category2 = Category::of('boots');
        $category3 = Category::of('sandals');

        self::assertTrue($category1->equals($category2));
        self::assertFalse($category1->equals($category3));
    }

    public function testFromPrimitives(): void
    {
        $category = Category::fromPrimitives('boots');

        self::assertSame('boots', $category->value());
    }
}
