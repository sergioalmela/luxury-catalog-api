<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiInfoController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function apiInfo(): JsonResponse
    {
        return new JsonResponse([
            'name' => 'My Theresa Product Catalog API',
            'version' => '1.0.0',
            'documentation' => '/api/doc',
            'endpoints' => [
                'products' => '/products',
                'health' => '/health',
            ],
            'timestamp' => new DateTimeImmutable()->format(DateTimeImmutable::ATOM),
        ]);
    }

    #[Route('/health', methods: ['GET'])]
    public function health(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'healthy',
            'timestamp' => new DateTimeImmutable()->format(DateTimeImmutable::ATOM),
        ]);
    }
}
