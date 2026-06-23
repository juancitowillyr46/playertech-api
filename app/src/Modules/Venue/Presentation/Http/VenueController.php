<?php

declare(strict_types=1);

namespace App\Modules\Venue\Presentation\Http;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Venue\Application\Command\CreateVenueCommand;
use App\Modules\Venue\Application\Dto\CreateVenueInput;
use App\Modules\Venue\Application\Handler\CreateVenueHandler;
use App\Modules\Venue\Application\Handler\ListVenuesHandler;
use App\Modules\Venue\Application\Query\ListVenuesQuery;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/venues')]
final class VenueController extends AbstractApiController
{
    public function __construct(
        private readonly CreateVenueHandler $createVenueHandler,
        private readonly ListVenuesHandler $listVenuesHandler,
        private readonly ValidatorInterface $validator,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_venues_list', methods: ['GET'])]
    public function list(TenantContext $tenantContext): JsonResponse
    {
        $venues = ($this->listVenuesHandler)(
            new ListVenuesQuery(
                new AcademyId(
                    $tenantContext->requireAcademyId()
                )
            )
        );

        return new JsonResponse([
            'data' => array_map(
                static fn ($item) => $item->toArray(),
                $venues
            ),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('', name: 'api_v1_venue_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateVenueInput::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createVenueHandler)(
            new CreateVenueCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $input
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }
}