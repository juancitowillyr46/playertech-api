<?php

declare(strict_types=1);

namespace App\Modules\Category\Presentation\Http\Open;

use App\Modules\Category\Application\Handler\ListOnboardingCategoriesHandler;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/public/categories')]
final class PublicCategoryController extends AbstractApiController
{
    public function __construct(
        private ListOnboardingCategoriesHandler $listOnboardingCategoriesHandler,
    ) {
    }

    #[Route('', name: 'api_v1_public_categories_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $categories = ($this->listOnboardingCategoriesHandler)();

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $categories),
            'meta' => new \stdClass(),
        ]);
    }
}
