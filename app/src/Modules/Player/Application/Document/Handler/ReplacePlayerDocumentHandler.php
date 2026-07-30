<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Document\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Document\Command\ReplacePlayerDocumentCommand;
use App\Modules\Player\Application\Document\Response\PlayerDocumentResponse;
use App\Modules\Player\Domain\Document\{DocumentType,PlayerDocumentId,PlayerDocumentRepository,PlayerDocumentStorage,PlayerDocumentUploadValidator};
use App\Modules\Player\Domain\Exception\PlayerDocumentNotFoundException;
use App\Modules\Player\Domain\Player\PlayerId;

final readonly class ReplacePlayerDocumentHandler
{
    public function __construct(private PlayerDocumentRepository $repository, private PlayerDocumentStorage $storage, private PlayerDocumentUploadValidator $validator) {}
    public function __invoke(ReplacePlayerDocumentCommand $command): PlayerDocumentResponse
    {
        $academy = new AcademyId($command->academyId);
        $document = $this->repository->findActiveById($academy, new PlayerId($command->playerId), new PlayerDocumentId($command->documentId)) ?? throw new PlayerDocumentNotFoundException();
        $type = DocumentType::fromInput($command->documentType);
        $file = $this->validator->validate($command->file);
        $file['storageName'] = $this->storage->store($command->file, $file['extension']);
        $old = $document->replace($type, $file, $command->observations, $command->actorId);
        try {
            $this->repository->save($document);
        } catch (\Throwable $e) {
            $this->storage->delete($file['storageName']);
            throw $e;
        }
        $this->storage->delete($old);
        return PlayerDocumentResponse::fromDocument($document);
    }
}
