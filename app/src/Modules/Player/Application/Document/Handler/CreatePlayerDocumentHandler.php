<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Document\Command\CreatePlayerDocumentCommand;
use App\Modules\Player\Application\Document\Response\PlayerDocumentResponse;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Document\{DocumentType,PlayerDocument,PlayerDocumentId,PlayerDocumentRepository,PlayerDocumentStorage,PlayerDocumentUploadValidator};
use App\Modules\Player\Domain\Player\PlayerId;
final readonly class CreatePlayerDocumentHandler
{
    public function __construct(private PlayerFinder $playerFinder, private PlayerDocumentRepository $repository, private PlayerDocumentStorage $storage, private PlayerDocumentUploadValidator $validator) {}
    public function __invoke(CreatePlayerDocumentCommand $command): PlayerDocumentResponse
    {
        $academy = new AcademyId($command->academyId); $player = $this->playerFinder->findOrFail($academy, new PlayerId($command->playerId));
        $type = DocumentType::fromInput($command->documentType); $file = $this->validator->validate($command->file); $file['storageName'] = $this->storage->store($command->file, $file['extension']);
        $document = PlayerDocument::create(PlayerDocumentId::generate(), $player->academyId(), $player->id(), $type, $file, $command->observations, $command->actorId);
        try { $this->repository->save($document); } catch (\Throwable $e) { $this->storage->delete($file['storageName']); throw $e; }
        return PlayerDocumentResponse::fromDocument($document);
    }
}
