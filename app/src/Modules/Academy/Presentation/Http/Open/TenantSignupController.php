<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Open;

use App\Modules\Academy\Application\Command\ActivateTenantCommand;
use App\Modules\Academy\Application\Command\RegisterTenantCommand;
use App\Modules\Academy\Application\Handler\ActivateTenantHandler;
use App\Modules\Academy\Application\Handler\RegisterTenantHandler;
use App\Modules\Academy\Presentation\Http\Request\TenantSignupRequest;
use App\Shared\Domain\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/public/tenants')]
final readonly class TenantSignupController
{
    public function __construct(
        private ValidatorInterface $validator,
        private RegisterTenantHandler $registerTenantHandler,
        private ActivateTenantHandler $activateTenantHandler,
    ) {
    }

    #[Route('/signup', name: 'api_v1_public_tenant_signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        $input = TenantSignupRequest::fromArray($request->toArray());
        $this->assertValid($input);

        $result = ($this->registerTenantHandler)(new RegisterTenantCommand($input->toInput()));

        return new JsonResponse([
            'data' => $result->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('/activate/{token}', name: 'api_v1_public_tenant_activate', methods: ['GET'])]
    public function activate(string $token): JsonResponse
    {
        $result = ($this->activateTenantHandler)(new ActivateTenantCommand($token));

        return new JsonResponse([
            'data' => $result->toArray(),
            'meta' => new \stdClass(),
        ]);
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
