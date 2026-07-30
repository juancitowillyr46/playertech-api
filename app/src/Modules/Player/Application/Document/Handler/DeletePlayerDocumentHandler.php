<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Document\Command\DeletePlayerDocumentCommand;
use App\Modules\Player\Domain\Document\{PlayerDocumentId,PlayerDocumentRepository,PlayerDocumentStorage};
use App\Modules\Player\Domain\Player\PlayerId;
final readonly class DeletePlayerDocumentHandler
{
    public function __construct(private PlayerDocumentRepository $repository, private PlayerDocumentStorage $storage) {}
    public function __invoke(DeletePlayerDocumentCommand $command): void
    {
        $document = $this->repository->findActiveById(new AcademyId($command->academyId), new PlayerId($command->playerId), new PlayerDocumentId($command->documentId)) ?? throw new \RuntimeException('Documento no encontrado.');
        $document->delete($command->actorId); $this->repository->save($document); $this->storage->delete($document->storageName());
    }
}
