<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Handler;
use App\Modules\Player\Application\Document\Query\DownloadPlayerDocumentQuery;
use App\Modules\Player\Application\Document\Response\PlayerDocumentDownloadResponse;
use App\Modules\Player\Domain\Document\PlayerDocumentRepository;
use App\Modules\Player\Domain\Document\PlayerDocumentStorage;
final readonly class DownloadPlayerDocumentHandler
{
    public function __construct(private PlayerDocumentRepository $repository, private PlayerDocumentStorage $storage) {}
    public function __invoke(DownloadPlayerDocumentQuery $query): PlayerDocumentDownloadResponse
    {
        $document = $this->repository->findActiveById($query->academyId, $query->playerId, $query->documentId) ?? throw new \RuntimeException('Documento no encontrado.');
        return new PlayerDocumentDownloadResponse($document, $this->storage->path($document->storageName()));
    }
}
