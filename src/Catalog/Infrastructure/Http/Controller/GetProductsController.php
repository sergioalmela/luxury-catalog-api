<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Controller;

use App\Catalog\Application\Query\GetProducts\GetProductsQuery;
use App\Shared\Infrastructure\Http\Controller\BaseController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class GetProductsController extends BaseController
{
    #[Route('/products', name: 'get_products', methods: ['GET'])]
    #[OA\Get(
        path: '/products',
        summary: 'Get products with optional filters',
        description: 'Returns a list of products with discounts applied. Max 5 products returned.',
        tags: ['Products']
    )]
    #[OA\Parameter(
        name: 'category',
        description: 'Filter products by category',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string', example: 'boots')
    )]
    #[OA\Parameter(
        name: 'priceLessThan',
        description: 'Filter products with price less than value (applied before discounts, in cents)',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', example: 80000)
    )]
    #[OA\Response(
        response: 200,
        description: 'Products list with discounts applied',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'products',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'sku', type: 'string', example: '000001'),
                            new OA\Property(property: 'name', type: 'string', example: 'BV Lean leather ankle boots'),
                            new OA\Property(property: 'category', type: 'string', example: 'boots'),
                            new OA\Property(
                                property: 'price',
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'original', type: 'integer', example: 89000),
                                    new OA\Property(property: 'final', type: 'integer', example: 62300),
                                    new OA\Property(property: 'discount_percentage', type: 'string', example: '30%'),
                                    new OA\Property(property: 'currency', type: 'string', example: 'EUR'),
                                ]
                            ),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid request parameters',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Invalid price provided'),
            ]
        )
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $getProductsQuery = new GetProductsQuery(
            $request->query->get('category'),
            $request->query->getInt('priceLessThan') ?: null
        );
        $response = $this->ask($getProductsQuery);

        return $this->jsonResponse($response->toArray());
    }
}
