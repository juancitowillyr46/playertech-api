<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Player\Application\Command\CreatePlayerCommand;
use App\Modules\Player\Application\Command\ImportPlayersCommand;
use App\Modules\Player\Application\Command\ActivatePlayerCommand;
use App\Modules\Player\Application\Command\InactivatePlayerCommand;
use App\Modules\Player\Application\Photo\Upload\UploadPlayerPhotoCommand;
use App\Modules\Player\Application\Photo\Upload\UploadPlayerPhotoHandler;
use App\Modules\Player\Application\Handler\ActivatePlayerHandler;
use App\Modules\Player\Application\Handler\CreatePlayerHandler;
use App\Modules\Player\Application\Handler\ImportPlayersHandler;
use App\Modules\Player\Application\Handler\ListPlayersHandler;
use App\Modules\Player\Application\Handler\InactivatePlayerHandler;
use App\Modules\Player\Application\Handler\UpdatePlayerHandler;
use App\Modules\Player\Application\Query\ListPlayersQuery;
use App\Modules\Player\Application\Handler\ShowPlayerHandler;
use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Application\Command\UpdatePlayerCommand;
use App\Modules\Player\Presentation\Http\Request\AssociateGuardianRequest;
use App\Modules\Player\Presentation\Http\Request\CreatePlayerRequest;
use App\Modules\Player\Presentation\Http\Request\UpdatePlayerRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/players')]
final class PlayerController extends AbstractPaginatedApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreatePlayerHandler $createPlayerHandler,
        private readonly ImportPlayersHandler $importPlayersHandler,
        private readonly ListPlayersHandler $listPlayersHandler,
        private readonly ShowPlayerHandler $showPlayerHandler,
        private readonly UpdatePlayerHandler $updatePlayerHandler,
        private readonly UploadPlayerPhotoHandler $uploadPlayerPhotoHandler,
        private readonly InactivatePlayerHandler $inactivatePlayerHandler,
        private readonly ActivatePlayerHandler $activatePlayerHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_academy_players_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreatePlayerRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createPlayerHandler)(
            new CreatePlayerCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('/import', name: 'api_v1_academy_players_import', methods: ['POST'])]
    public function import(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('El archivo Excel es obligatorio.');
        }

        if ('xlsx' !== strtolower((string) $file->getClientOriginalExtension())) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('El archivo debe tener extensión .xlsx.');
        }

        $players = ($this->importPlayersHandler)(
            new ImportPlayersCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $file
            )
        );

        return new JsonResponse([
            'data' => array_map(
                static fn ($item) => $item->toArray(),
                $players
            ),
            'meta' => [
                'imported_count' => count($players),
            ],
        ], 201);
    }

    #[Route('', name: 'api_v1_academy_players_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $players = ($this->listPlayersHandler)(
            new ListPlayersQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value')
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $players->items),
            'meta' => $players->meta->toArray(),
        ]);
    }

    #[Route('/{playerId}', name: 'api_v1_academy_players_show', methods: ['GET'])]
    public function show(string $playerId): JsonResponse
    {
        $view = ($this->showPlayerHandler)(
            new ShowPlayerQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId)
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{playerId}', name: 'api_v1_academy_players_update', methods: ['PUT'])]
    public function update(Request $request, string $playerId): JsonResponse
    {
        $input = UpdatePlayerRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updatePlayerHandler)(
            new UpdatePlayerCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $playerId,
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{playerId}/photo', name: 'api_v1_academy_players_photo', methods: ['PATCH'])]
    public function updatePhoto(Request $request, string $playerId): JsonResponse
    {
        /** @var ?UploadedFile $photoFile */
        $photoFile = $request->files->get('photo');

        if (null === $photoFile) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('"photo" file is required.');
        }

        $view = ($this->uploadPlayerPhotoHandler)(
            new UploadPlayerPhotoCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $playerId,
                $photoFile,
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{playerId}/inactivate', name: 'api_v1_academy_players_inactivate', methods: ['PATCH'])]
    public function inactivate(string $playerId): Response
    {
        ($this->inactivatePlayerHandler)(
            new InactivatePlayerCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $playerId
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/{playerId}/activate', name: 'api_v1_academy_players_activate', methods: ['PATCH'])]
    public function activate(string $playerId): Response
    {
        ($this->activatePlayerHandler)(
            new ActivatePlayerCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $playerId
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    private function requireActorId(): string
    {
        return $this->requireAuthenticatedUserId($this->security);
    }
}
