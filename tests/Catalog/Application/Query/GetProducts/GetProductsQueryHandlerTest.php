<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Query\GetProducts;

use App\Catalog\Application\Query\GetProducts\GetProductsQuery;
use App\Catalog\Application\Query\GetProducts\GetProductsQueryHandler;
use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\Service\DiscountCalculator;
use App\Tests\Catalog\Infrastructure\Testing\Builders\ProductBuilder;
use App\Tests\Catalog\Infrastructure\Testing\Doubles\ProductRepositoryFake;
use PHPUnit\Framework\TestCase;

final class GetProductsQueryHandlerTest extends TestCase
{
    private ProductRepositoryFake $repository;
    private DiscountCalculator $discountCalculator;
    private GetProductsQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = new ProductRepositoryFake();
        $this->discountCalculator = new DiscountCalculator();
        $this->handler = new GetProductsQueryHandler($this->repository, $this->discountCalculator);
    }

    public function testGetAllProductsWithDiscountsApplied(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()->withSKU('000001')->withBootsCategory()->withPrice(89000)->build(),
            ProductBuilder::create()->withSKU('000002')->withSandalsCategory()->withPrice(79500)->build(),
            ProductBuilder::create()->withSpecialSKU()->withBootsCategory()->withPrice(71000)->build(),
        ]);

        $query = new GetProductsQuery();

        $response = $this->handler->__invoke($query);

        $products = $response->products;
        self::assertCount(3, $products);

        // First product (boots category - 30% discount)
        self::assertSame('000001', $products[0]['sku']);
        self::assertSame(89000, $products[0]['price']['original']);
        self::assertSame(62300, $products[0]['price']['final']);
        self::assertSame('30%', $products[0]['price']['discount_percentage']);

        // Second product (no discount)
        self::assertSame('000002', $products[1]['sku']);
        self::assertSame(79500, $products[1]['price']['original']);
        self::assertSame(79500, $products[1]['price']['final']);
        self::assertNull($products[1]['price']['discount_percentage']);

        // Third product (special SKU + boots - highest discount 30%)
        self::assertSame('000003', $products[2]['sku']);
        self::assertSame(71000, $products[2]['price']['original']);
        self::assertSame(49700, $products[2]['price']['final']);
        self::assertSame('30%', $products[2]['price']['discount_percentage']);
    }

    public function testGetProductsFilteredByCategory(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()->withSKU('000001')->withBootsCategory()->withPrice(89000)->build(),
            ProductBuilder::create()->withSKU('000002')->withSandalsCategory()->withPrice(79500)->build(),
            ProductBuilder::create()->withSKU('000003')->withBootsCategory()->withPrice(99000)->build(),
        ]);

        $query = new GetProductsQuery('boots');

        $response = $this->handler->__invoke($query);

        $products = $response->products;

        self::assertCount(2, $products);
        self::assertSame('000001', $products[0]['sku']);
        self::assertSame('000003', $products[1]['sku']);
        self::assertSame('boots', $products[0]['category']);
        self::assertSame('boots', $products[1]['category']);
    }

    public function testGetProductsFilteredByMaxPrice(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()->withSKU('000001')->withBootsCategory()->withPrice(89000)->build(),
            ProductBuilder::create()->withSKU('000002')->withSandalsCategory()->withPrice(79500)->build(),
            ProductBuilder::create()->withSKU('000003')->withBootsCategory()->withPrice(99000)->build(),
        ]);

        $query = new GetProductsQuery(null, 80000);

        $response = $this->handler->__invoke($query);

        $products = $response->products;
        self::assertCount(1, $products);
        self::assertSame('000002', $products[0]['sku']);
        self::assertSame(79500, $products[0]['price']['original']);
    }

    public function testGetProductsFilteredByCategoryAndMaxPrice(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()->withSKU('000001')->withBootsCategory()->withPrice(89000)->build(),
            ProductBuilder::create()->withSKU('000002')->withSandalsCategory()->withPrice(79500)->build(),
            ProductBuilder::create()->withSKU('000003')->withBootsCategory()->withPrice(70000)->build(),
        ]);

        $query = new GetProductsQuery('boots', 80000);

        $response = $this->handler->__invoke($query);

        $products = $response->products;
        self::assertCount(1, $products);
        self::assertSame('000003', $products[0]['sku']);
        self::assertSame('boots', $products[0]['category']);
        self::assertSame(70000, $products[0]['price']['original']);
    }

    public function testGetProductsRespectsLimitOfFive(): void
    {
        $products = [];
        for ($i = 1; $i <= 10; ++$i) {
            $products[] = ProductBuilder::create()
                ->withSKU(\sprintf('00000%d', $i))
                ->withSneakersCategory()
                ->withPrice(50000 + ($i * 1000))
                ->build();
        }
        $this->givenThereAreProductsInTheRepository($products);

        $query = new GetProductsQuery();

        $response = $this->handler->__invoke($query);

        $resultProducts = $response->products;
        self::assertCount(5, $resultProducts);
    }

    public function testGetProductsWithEmptyRepository(): void
    {
        $query = new GetProductsQuery();

        $response = $this->handler->__invoke($query);

        $products = $response->products;
        self::assertCount(0, $products);
    }

    public function testResponseFormatsCorrectly(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()
                ->withSKU('000001')
                ->withName('BV Lean leather ankle boots')
                ->withBootsCategory()
                ->withPrice(89000)
                ->build(),
        ]);

        $query = new GetProductsQuery();

        $response = $this->handler->__invoke($query);

        $responseArray = $response->toArray();
        self::assertArrayHasKey('products', $responseArray);

        $product = $responseArray['products'][0];
        self::assertSame('000001', $product['sku']);
        self::assertSame('BV Lean leather ankle boots', $product['name']);
        self::assertSame('boots', $product['category']);
        self::assertArrayHasKey('price', $product);
        self::assertSame(89000, $product['price']['original']);
        self::assertSame(62300, $product['price']['final']);
        self::assertSame('30%', $product['price']['discount_percentage']);
        self::assertSame('EUR', $product['price']['currency']);
    }

    public function testGetProductsFilteredByCategoryAppliesDiscounts(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()->withSKU('000001')->withBootsCategory()->withPrice(89000)->build(),
            ProductBuilder::create()->withSKU('000002')->withSandalsCategory()->withPrice(79500)->build(),
        ]);

        $query = new GetProductsQuery('boots');

        $response = $this->handler->__invoke($query);

        $products = $response->products;
        self::assertCount(1, $products);
        self::assertSame('000001', $products[0]['sku']);
        self::assertSame('boots', $products[0]['category']);
        self::assertSame(89000, $products[0]['price']['original']);
        self::assertSame(62300, $products[0]['price']['final']);
        self::assertSame('30%', $products[0]['price']['discount_percentage']);
    }

    public function testPriceFilterAppliedBeforeDiscount(): void
    {
        $this->givenThereAreProductsInTheRepository([
            ProductBuilder::create()->withSKU('000001')->withBootsCategory()->withPrice(80000)->build(),
            ProductBuilder::create()->withSKU('000002')->withSandalsCategory()->withPrice(70000)->build(),
        ]);

        $query = new GetProductsQuery(null, 75000);

        $response = $this->handler->__invoke($query);

        $products = $response->products;
        self::assertCount(1, $products);
        self::assertSame('000002', $products[0]['sku']);
        self::assertSame('sandals', $products[0]['category']);
        self::assertSame(70000, $products[0]['price']['original']);
        self::assertSame(70000, $products[0]['price']['final']);
        self::assertNull($products[0]['price']['discount_percentage']);
    }

    /**
     * @param Product[] $products
     */
    private function givenThereAreProductsInTheRepository(array $products): void
    {
        foreach ($products as $product) {
            $this->repository->add($product);
        }
    }
}
