<?php

declare(strict_types=1);

namespace App\Modules\Category\Presentation\Http;

use App\Modules\Category\Application\Command\CreateCategoryCommand;
use App\Modules\Category\Application\Dto\CreateCategoryInput;
use App\Modules\Category\Application\Handler\CreateCategoryHandler;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Presentation\Http\AbstractApiController;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/categories')]
final class CategoryController extends AbstractApiController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly CreateCategoryHandler $createCategoryHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_categories_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse 
    {
        $input = CreateCategoryInput::fromArray($request->toArray());

        $this->assertValid($this->validator, $input);

        $view = ($this->createCategoryHandler)(
            new CreateCategoryCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $input,
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

}