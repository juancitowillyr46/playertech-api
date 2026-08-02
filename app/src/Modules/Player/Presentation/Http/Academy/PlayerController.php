<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Player\Application\Command\CreatePlayerCommand;
use App\Modules\Player\Application\Photo\Delete\DeletePlayerPhotoCommand;
use App\Modules\Player\Application\Photo\Delete\DeletePlayerPhotoHandler;
use App\Modules\Player\Application\Command\ActivatePlayerCommand;
use App\Modules\Player\Application\Command\InactivatePlayerCommand;
use App\Modules\Player\Application\Photo\Upload\UploadPlayerPhotoCommand;
use App\Modules\Player\Application\Photo\Upload\UploadPlayerPhotoHandler;
use App\Modules\Player\Application\Handler\ActivatePlayerHandler;
use App\Modules\Player\Application\Handler\CreatePlayerHandler;
use App\Modules\Player\Application\Import\ProcessPlayerImportJobHandler;
use App\Modules\Player\Application\Handler\ListPlayersHandler;
use App\Modules\Player\Application\Handler\InactivatePlayerHandler;
use App\Modules\Player\Application\Handler\UpdatePlayerHandler;
use App\Modules\Player\Application\Query\ListPlayersQuery;
use App\Modules\Player\Application\Handler\ShowPlayerHandler;
use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Application\Response\PlayerImportJobResponse;
use App\Modules\Player\Application\Service\PlayerImportTemplateFactory;
use App\Modules\Player\Domain\Import\PlayerImportJob;
use App\Modules\Player\Domain\Import\PlayerImportJobId;
use App\Modules\Player\Domain\Import\PlayerImportJobRepository;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Application\Command\UpdatePlayerCommand;
use App\Modules\Player\Presentation\Http\Request\AssociateGuardianRequest;
use App\Modules\Player\Presentation\Http\Request\CreatePlayerRequest;
use App\Modules\Player\Presentation\Http\Request\UpdatePlayerRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
        private readonly ListPlayersHandler $listPlayersHandler,
        private readonly ShowPlayerHandler $showPlayerHandler,
        private readonly UpdatePlayerHandler $updatePlayerHandler,
        private readonly UploadPlayerPhotoHandler $uploadPlayerPhotoHandler,
        private readonly DeletePlayerPhotoHandler $deletePlayerPhotoHandler,
        private readonly InactivatePlayerHandler $inactivatePlayerHandler,
        private readonly ActivatePlayerHandler $activatePlayerHandler,
        private readonly PlayerImportTemplateFactory $playerImportTemplateFactory,
        private readonly PlayerImportJobRepository $playerImportJobRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly ProcessPlayerImportJobHandler $processPlayerImportJobHandler,
        private readonly string $projectDir,
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
        /** @var ?UploadedFile $file */
        $file = $request->files->get('file');
        $categoryId = trim((string) $request->request->get('categoryId', ''));

        if (!$file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('El archivo Excel es obligatorio.');
        }

        if ('xlsx' !== strtolower((string) $file->getClientOriginalExtension())) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('El archivo debe tener extensión .xlsx.');
        }

        if ('' === $categoryId) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('La categoría es obligatoria.');
        }

        $academyId = new AcademyId($this->tenantContext->requireAcademyId());
        $category = $this->categoryRepository->findById($academyId, new CategoryId($categoryId));

        if (null === $category) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('La categoría seleccionada no existe o no pertenece a la academia.');
        }

        $jobId = PlayerImportJobId::generate();
        $actorId = $this->requireActorId();
        $importDirectory = $this->ensureImportDirectory();
        $storedPath = $importDirectory . '/' . $jobId->value() . '.xlsx';
        $file->move($importDirectory, $jobId->value() . '.xlsx');
        $job = PlayerImportJob::create(
            $jobId,
            $academyId,
            $actorId,
            $categoryId,
            $file->getClientOriginalName(),
            $storedPath
        );

        $this->playerImportJobRepository->save($job);

        register_shutdown_function(function () use ($jobId, $academyId, $actorId, $categoryId): void {
            ($this->processPlayerImportJobHandler)(
                new \App\Modules\Player\Application\Import\ProcessPlayerImportJobMessage(
                    $academyId->value(),
                    $jobId->value(),
                    $actorId,
                    $categoryId,
                )
            );
        });

        return new JsonResponse([
            'data' => [
                'jobId' => $jobId->value(),
                'status' => 'QUEUED',
            ],
            'meta' => new \stdClass(),
        ], 202);
    }

    #[Route('/import/template', name: 'api_v1_academy_players_import_template', methods: ['GET'])]
    public function importTemplate(Request $request): BinaryFileResponse
    {
        $spreadsheet = $this->playerImportTemplateFactory->create(
            new AcademyId($this->tenantContext->requireAcademyId())
        );

        $path = sys_get_temp_dir() . '/' . uniqid('player-import-template-', true) . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'plantilla-importacion-jugadores.xlsx'
        );
        $response->deleteFileAfterSend(true);

        return $response;
    }

    #[Route('/import/{jobId}', name: 'api_v1_academy_players_import_show', methods: ['GET'])]
    public function showImport(string $jobId): JsonResponse
    {
        $job = $this->playerImportJobRepository->findById(
            new AcademyId($this->tenantContext->requireAcademyId()),
            new PlayerImportJobId($jobId)
        );

        if (null === $job) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Import job not found.');
        }

        return new JsonResponse([
            'data' => PlayerImportJobResponse::fromJob($job)->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    private function ensureImportDirectory(): string
    {
        $directory = $this->projectDir . '/var/player-imports';
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new \RuntimeException('Could not create import directory.');
        }

        return $directory;
    }

    #[Route('', name: 'api_v1_academy_players_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $players = ($this->listPlayersHandler)(
            new ListPlayersQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value'),
                $this->nullableQueryString($request, 'gender'),
                $this->nullableQueryString($request, 'categoryId'),
                $this->nullableQueryString($request, 'documentNumber'),
                $this->nullableQueryString($request, 'documentType'),
                $this->nullableQueryString($request, 'firstName'),
                $this->nullableQueryString($request, 'lastName'),
                $this->nullableQueryString($request, 'fullName'),
                $this->nullableQueryString($request, 'createdAtFrom'),
                $this->nullableQueryString($request, 'createdAtTo'),
                $this->nullableQueryString($request, 'birthDateFrom'),
                $this->nullableQueryString($request, 'birthDateTo'),
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

    #[Route('/{playerId}/photo', name: 'api_v1_academy_players_photo_delete', methods: ['DELETE'])]
    public function deletePhoto(string $playerId): Response
    {
        ($this->deletePlayerPhotoHandler)(
            new DeletePlayerPhotoCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $playerId
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
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

    private function nullableQueryString(Request $request, string $key): ?string
    {
        $value = trim((string) $request->query->get($key, ''));

        return '' === $value ? null : $value;
    }
}
