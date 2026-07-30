<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Handler;
use App\Modules\Player\Application\Document\Query\ShowPlayerDocumentQuery;
use App\Modules\Player\Application\Document\Response\PlayerDocumentFileResponse;
use App\Modules\Player\Domain\Document\PlayerDocumentRepository;
use App\Modules\Player\Domain\Document\PlayerDocumentStorage;
readonly class ShowPlayerDocumentHandler
{
    public function __construct(private PlayerDocumentRepository $repository, private PlayerDocumentStorage $storage) {}
    public function __invoke(ShowPlayerDocumentQuery $query): PlayerDocumentFileResponse
    {
        $document = $this->repository->findActiveById($query->academyId, $query->playerId, $query->documentId) ?? throw new \RuntimeException('Documento no encontrado.');
        return new PlayerDocumentFileResponse($document, $this->storage->path($document->storageName()));
    }
}
