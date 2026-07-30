<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Document;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Player\Application\Document\Command\{CreatePlayerDocumentCommand,DeletePlayerDocumentCommand,ReplacePlayerDocumentCommand};
use App\Modules\Player\Application\Document\Handler\{CreatePlayerDocumentHandler,DeletePlayerDocumentHandler,DownloadPlayerDocumentHandler,ListPlayerDocumentsHandler,ReplacePlayerDocumentHandler,ShowPlayerDocumentHandler};
use App\Modules\Player\Application\Document\Query\{DownloadPlayerDocumentQuery,ListPlayerDocumentsQuery,ShowPlayerDocumentQuery};
use App\Modules\Player\Application\Document\Response\PlayerDocumentFileResponse;
use App\Modules\Player\Domain\Document\PlayerDocumentId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Presentation\Http\Document\Request\CreatePlayerDocumentRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\{BinaryFileResponse,JsonResponse,Request,Response,ResponseHeaderBag};
use Symfony\Component\Routing\Attribute\Route;

#[Route('/academy/players/{playerId}/documents')]
final class PlayerDocumentController extends AbstractPaginatedApiController
{
    public function __construct(private readonly Security $security, private readonly TenantContext $tenantContext, private readonly ListPlayerDocumentsHandler $listHandler, private readonly CreatePlayerDocumentHandler $createHandler, private readonly ShowPlayerDocumentHandler $showHandler, private readonly DownloadPlayerDocumentHandler $downloadHandler, private readonly ReplacePlayerDocumentHandler $replaceHandler, private readonly DeletePlayerDocumentHandler $deleteHandler) {}

    #[Route('', methods: ['GET'])]
    public function list(Request $request, string $playerId): JsonResponse
    {
        $this->assertAdmin(); $result = ($this->listHandler)(new ListPlayerDocumentsQuery($this->academy(), new PlayerId($playerId), $this->paginationQueryFromRequest($request)));
        return new JsonResponse(['data' => array_map(static fn ($item) => $item->toArray(), $result->items), 'meta' => $result->meta->toArray()]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, string $playerId): JsonResponse
    {
        $this->assertAdmin(); $input = CreatePlayerDocumentRequest::fromRequest($request); $result = ($this->createHandler)(new CreatePlayerDocumentCommand($this->actor(), $this->tenantContext->requireAcademyId(), $playerId, $input->documentType, $input->file, $input->observations));
        return new JsonResponse(['data' => $result->toArray(), 'meta' => new \stdClass()], Response::HTTP_CREATED);
    }

    #[Route('/{documentId}', methods: ['GET'])]
    public function view(string $playerId, string $documentId): BinaryFileResponse { $this->assertAdmin(); return $this->binary(($this->showHandler)(new ShowPlayerDocumentQuery($this->academy(), new PlayerId($playerId), new PlayerDocumentId($documentId))), ResponseHeaderBag::DISPOSITION_INLINE); }
    #[Route('/{documentId}/download', methods: ['GET'])]
    public function download(string $playerId, string $documentId): BinaryFileResponse { $this->assertAdmin(); return $this->binary(($this->downloadHandler)(new DownloadPlayerDocumentQuery($this->academy(), new PlayerId($playerId), new PlayerDocumentId($documentId))), ResponseHeaderBag::DISPOSITION_ATTACHMENT); }
    #[Route('/{documentId}', methods: ['PUT'])]
    public function replace(Request $request, string $playerId, string $documentId): JsonResponse { $this->assertAdmin(); $input = CreatePlayerDocumentRequest::fromRequest($request); $result = ($this->replaceHandler)(new ReplacePlayerDocumentCommand($this->actor(), $this->tenantContext->requireAcademyId(), $playerId, $documentId, $input->documentType, $input->file, $input->observations)); return new JsonResponse(['data' => $result->toArray(), 'meta' => new \stdClass()]); }
    #[Route('/{documentId}', methods: ['DELETE'])]
    public function delete(string $playerId, string $documentId): Response { $this->assertAdmin(); ($this->deleteHandler)(new DeletePlayerDocumentCommand($this->actor(), $this->tenantContext->requireAcademyId(), $playerId, $documentId)); return new Response('', Response::HTTP_NO_CONTENT); }

    private function academy(): \App\Modules\Academy\Domain\Academy\AcademyId { return new \App\Modules\Academy\Domain\Academy\AcademyId($this->tenantContext->requireAcademyId()); }
    private function actor(): string { return $this->requireAuthenticatedUserId($this->security); }
    private function assertAdmin(): void { if (!$this->security->isGranted('ROLE_ACADEMY_ADMIN')) { throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Solo Owner/Admin puede gestionar documentos.'); } }
    private function binary(PlayerDocumentFileResponse $result, string $disposition): BinaryFileResponse { $response = new BinaryFileResponse($result->path); $response->headers->set('Content-Type', $result->document->mimeType()); $response->headers->set('X-Content-Type-Options', 'nosniff'); $response->setContentDisposition($disposition, $result->document->originalFileName()); return $response; }
}
