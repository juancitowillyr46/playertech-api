<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http;

use App\Modules\Academy\Application\Command\UpdateAcademyCommand;
use App\Modules\Academy\Application\Dto\UpdateAcademyInput;
use App\Modules\Academy\Application\Handler\GetAcademyContextHandler;
use App\Modules\Academy\Application\Handler\UpdateAcademyHandler;
use App\Modules\Academy\Application\Query\GetAcademyContextQuery;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AcademyMeController
{
    public function __construct(
        private readonly GetAcademyContextHandler $getAcademyContextHandler,
        private readonly UpdateAcademyHandler $updateAcademyHandler,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/academy/me', name: 'api_v1_academy_me', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $view = ($this->getAcademyContextHandler)(new GetAcademyContextQuery());

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me', name: 'api_v1_academy_me_update', methods: ['PUT'])]
    public function update(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $input = UpdateAcademyInput::fromArray($request->toArray());
        $this->assertValid($input);

        $view = ($this->updateAcademyHandler)(
            new UpdateAcademyCommand(
                $this->requireActorId($tenantContext),
                $tenantContext->requireAcademyId(),
                $input
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    private function requireActorId(TenantContext $tenantContext): string
    {
        $actorId = $tenantContext->getUserId();

        if (null === $actorId || '' === $actorId) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $actorId;
    }

    private function assertValid(object $input): void
    {
        $violations = $this->validator->validate($input);

        if (0 === count($violations)) {
            return;
        }

        throw new ValidationException($violations);
    }
}
