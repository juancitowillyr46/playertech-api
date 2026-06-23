<?php

declare(strict_types=1);

namespace App\Modules\Venue\Presentation\Http;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Domain\Venue\venueId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Venue\Application\Command\ActiveVenueCommand;
use App\Modules\Venue\Application\Command\CreateVenueCommand;
use App\Modules\Venue\Application\Command\DeleteVenueCommand;
use App\Modules\Venue\Application\Command\InactiveVenueCommand;
use App\Modules\Venue\Application\Command\UpdateVenueCommand;
use App\Modules\Venue\Application\Dto\CreateVenueInput;
use App\Modules\Venue\Application\Dto\UpdateVenueInput;
use App\Modules\Venue\Application\Handler\ActivateVenueHandler;
use App\Modules\Venue\Application\Handler\CreateVenueHandler;
use App\Modules\Venue\Application\Handler\DeleteVenueHandler;
use App\Modules\Venue\Application\Handler\InactivateVenueHandler;
use App\Modules\Venue\Application\Handler\ListVenuesHandler;
use App\Modules\Venue\Application\Handler\ShowVenueHandler;
use App\Modules\Venue\Application\Handler\UpdateVenueHandler;
use App\Modules\Venue\Application\Query\ListVenuesQuery;
use App\Modules\Venue\Application\Query\ShowVenueQuery;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/academy/venues')]
final class VenueController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly CreateVenueHandler $createVenueHandler,
        private readonly ListVenuesHandler $listVenuesHandler,
        private readonly ShowVenueHandler $showVenueHandler,
        private readonly UpdateVenueHandler $updateVenueHandler,
        private readonly DeleteVenueHandler $deleteVenueHandler,
        private readonly InactivateVenueHandler $inactivateVenueHandler,
        private readonly ActivateVenueHandler $activateVenueHandler,
        private readonly ValidatorInterface $validator,
        private readonly TenantContext $tenantContext,
    ) {
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

    #[Route('/{venueId}', name: 'api_v1_venues_show', methods: ['GET'])]
    public function show(string $venueId): JsonResponse
    {
        $view = ($this->showVenueHandler)(new ShowVenueQuery($venueId));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{venueId}', name: 'api_v1_venues_update', methods: ['PUT'])]
    public function update(string $venueId, Request $request): JsonResponse
    {
        $input = UpdateVenueInput::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateVenueHandler)(
            new UpdateVenueCommand(
                $this->requireActorId(),
                $venueId,
                $input
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{venueId}/inactivate', name: 'api_v1_venues_inactivate', methods: ['POST'])]
    public function reactivate(string $venueId): JsonResponse
    {
        $view = ($this->inactivateVenueHandler)(
            new InactiveVenueCommand(
                $this->requireActorId(),
                $venueId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{venueId}/activate', name: 'api_v1_venues_activate', methods: ['POST'])]
    public function activate(string $venueId): JsonResponse
    {
        $view = ($this->activateVenueHandler)(
            new ActiveVenueCommand(
                $this->requireActorId(),
                $venueId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{venueId}', name: 'api_v1_venues_delete', methods: ['DELETE'])]
    public function delete(string $venueId): JsonResponse
    {
         $view = ($this->deleteVenueHandler)(
            new DeleteVenueCommand(
                $this->requireActorId(),
                $venueId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    private function requireActorId(): string
    {
        return $this->requireAuthenticatedUserId($this->security);
    }

}