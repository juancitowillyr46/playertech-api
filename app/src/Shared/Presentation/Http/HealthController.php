<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HealthController
{
    #[Route('/health', name: 'api_v1_health', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'status' => 'ok =D',
            ],
            'meta' => new \stdClass(),
        ], Response::HTTP_OK);
    }
}
