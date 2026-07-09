<?php

declare(strict_types=1);

namespace App\Modules\Venue\Presentation\Http;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Venue\Application\Command\ActiveVenueCommand;
use App\Modules\Venue\Application\Command\CreateVenueCommand;
use App\Modules\Venue\Application\Command\DeleteVenueCommand;
use App\Modules\Venue\Application\Command\InactiveVenueCommand;
use App\Modules\Venue\Application\Command\UpdateVenueCommand;
use App\Modules\Venue\Application\Handler\ActivateVenueHandler;
use App\Modules\Venue\Application\Handler\CreateVenueHandler;
use App\Modules\Venue\Application\Handler\DeleteVenueHandler;
use App\Modules\Venue\Application\Handler\InactivateVenueHandler;
use App\Modules\Venue\Application\Handler\ListVenuesHandler;
use App\Modules\Venue\Application\Handler\ShowVenueHandler;
use App\Modules\Venue\Application\Handler\UpdateVenueHandler;
use App\Modules\Venue\Application\Query\ListVenuesQuery;
use App\Modules\Venue\Application\Query\ShowVenueQuery;
use App\Modules\Venue\Presentation\Http\Request\CreateVenueRequest;
use App\Modules\Venue\Presentation\Http\Request\UpdateVenueRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

#[Route('/academy/venues')]
final class VenueController extends AbstractPaginatedApiController
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
        $input = CreateVenueRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createVenueHandler)(
            new CreateVenueCommand(
                $this->tenantContext->getUserId(),
                $this->tenantContext->requireAcademyId(),
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('', name: 'api_v1_venues_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $venues = ($this->listVenuesHandler)(
            new ListVenuesQuery(
                new AcademyId(
                    $this->tenantContext->requireAcademyId()
                ),
                $this->paginationQueryFromRequest($request, 'audit_trail.created_at.value')
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $venues->items),
            'meta' => $venues->meta->toArray(),
        ]);
    }

    #[Route('/{venueId}', name: 'api_v1_venues_show', methods: ['GET'])]
    public function show(string $venueId): JsonResponse
    {
        $view = ($this->showVenueHandler)(new ShowVenueQuery(
            new AcademyId($this->tenantContext->requireAcademyId()),
            new \App\Modules\Venue\Domain\Venue\VenueId($venueId)
        ));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{venueId}', name: 'api_v1_venues_update', methods: ['PUT'])]
    public function update(string $venueId, Request $request): JsonResponse
    {
        $input = UpdateVenueRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateVenueHandler)(
            new UpdateVenueCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $venueId,
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{venueId}/inactivate', name: 'api_v1_venues_inactivate', methods: ['PATCH'])]
    public function reactivate(string $venueId): Response
    {
        ($this->inactivateVenueHandler)(
            new InactiveVenueCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $venueId
            )
        );

        return new Response(
            status: Response::HTTP_NO_CONTENT,
        );
    }

    #[Route('/{venueId}/activate', name: 'api_v1_venues_activate', methods: ['PATCH'])]
    public function activate(string $venueId): Response
    {
        ($this->activateVenueHandler)(
            new ActiveVenueCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $venueId
            )
        );

        return new Response(
            status: Response::HTTP_NO_CONTENT,
        );
    }

    #[Route('/{venueId}', name: 'api_v1_venues_delete', methods: ['DELETE'])]
    public function delete(string $venueId): Response
    {
         ($this->deleteVenueHandler)(
            new DeleteVenueCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $venueId
            )
        );

        return new Response(
            status: Response::HTTP_NO_CONTENT,
        );
    }

    private function requireActorId(): string
    {
        return $this->requireAuthenticatedUserId($this->security);
    }

}
