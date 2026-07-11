<?php

declare(strict_types=1);

namespace App\Modules\Category\Presentation\Http;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\ActivateCategoryCommand;
use App\Modules\Category\Application\Command\CreateCategoryCommand;
use App\Modules\Category\Application\Command\InactivateCategoryCommand;
use App\Modules\Category\Application\Command\UpdateCategoryCommand;
use App\Modules\Category\Application\Handler\ActivateCategoryHandler;
use App\Modules\Category\Application\Handler\CreateCategoryHandler;
use App\Modules\Category\Application\Handler\InactivateCategoryHandler;
use App\Modules\Category\Application\Handler\ListCategoriesHandler;
use App\Modules\Category\Application\Handler\ShowCategoryHandler;
use App\Modules\Category\Application\Handler\UpdateCategoryHandler;
use App\Modules\Category\Application\Query\ListCategoriesQuery;
use App\Modules\Category\Application\Query\ShowCategoryQuery;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Category\Presentation\Http\Request\CreateCategoryRequest;
use App\Modules\Category\Presentation\Http\Request\UpdateCategoryRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/categories')]
final class CategoryController extends AbstractPaginatedApiController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly CreateCategoryHandler $createCategoryHandler,
        private readonly UpdateCategoryHandler $updateCategoryHandler,
        private readonly ListCategoriesHandler $listCategoriesHandler,
        private readonly ShowCategoryHandler $showCategoryHandler,
        private readonly InactivateCategoryHandler $inactivateCategoryHandler,
        private readonly ActivateCategoryHandler $activateCategoryHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_categories_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse 
    {
        $input = CreateCategoryRequest::fromArray($request->toArray());

        $this->assertValid($this->validator, $input);

        $view = ($this->createCategoryHandler)(
            new CreateCategoryCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('', name: 'api_v1_categories_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $categories = ($this->listCategoriesHandler)(
            new ListCategoriesQuery(
                new AcademyId(
                    $this->tenantContext->requireAcademyId()
                ),
                $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value')
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $categories->items),
            'meta' => $categories->meta->toArray(),
        ]);
    }

    #[Route('/{categoryId}', name: 'api_v1_categories_update', methods: ['PUT'])]
    public function update(
        string $categoryId,
        Request $request,
    ): Response {

        $input = UpdateCategoryRequest::fromArray($request->toArray());
        
        $this->assertValid($this->validator, $input);

        ($this->updateCategoryHandler)(
            new UpdateCategoryCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $categoryId,
                $input->toInput()
            )
        );

        return new Response(
            status: Response::HTTP_NO_CONTENT,
        );
        
    }

    #[Route('/{categoryId}', name: 'api_v1_categories_show', methods: ['GET'])]
    public function show(string $categoryId): JsonResponse {
        $category = ($this->showCategoryHandler)(
            new ShowCategoryQuery(
                new AcademyId(
                    $this->tenantContext->requireAcademyId()
                ),
                new CategoryId($categoryId),
            )
        );

        return new JsonResponse([
            'data' => $category->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{categoryId}/inactivate', name: 'api_v1_categories_inactivate', methods: ['PATCH'])]
    public function inactivate(string $categoryId): Response
    {
        ($this->inactivateCategoryHandler)(
            new InactivateCategoryCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $categoryId,
            )
        );

        return new Response(
            status: Response::HTTP_NO_CONTENT,
        );
    }

    #[Route('/{categoryId}/activate', name: 'api_v1_categories_activate', methods: ['PATCH'])]
    public function activate(string $categoryId): Response
    {
        ($this->activateCategoryHandler)(
            new ActivateCategoryCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $categoryId,
            )
        );

        return new Response(
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
