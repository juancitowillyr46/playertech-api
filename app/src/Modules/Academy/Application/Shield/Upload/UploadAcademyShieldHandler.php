<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Shield\Upload;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Domain\Contracts\FileStorage;

final class UploadAcademyShieldHandler implements CommandHandler
{
    public function __construct(
        private readonly AcademyRepository $academyRepository,
        private readonly FileStorage $fileStorage,
    ) {
    }

    public function __invoke(UploadAcademyShieldCommand $command): void
    {
        $academyId = new AcademyId($command->getRelatedId());
        $academy = $this->academyRepository->get($academyId);

        $this->fileStorage->delete($academy->shield());

        $media = $this->fileStorage->upload(
            $command->getFile(),
            'images/academies/' . $academy->id()->value()
        );

        $academy->updateShield($media, $command->getUserId());

        $this->academyRepository->save($academy);
    }
}
