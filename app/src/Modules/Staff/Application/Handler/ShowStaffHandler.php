<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Query\ShowStaffQuery;
use App\Modules\Staff\Application\Response\StaffDetailResponse;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ShowStaffHandler
{
    public function __construct(
        private StaffRepository $staffRepository,
        private \Doctrine\ORM\EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ShowStaffQuery $query): StaffDetailResponse
    {
        $academyId = new AcademyId($query->academyId->value());
        $staff = $this->staffRepository->findByUserId($academyId, $query->userId);

        if (null === $staff) {
            throw new NotFoundHttpException('Staff no encontrado.');
        }

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->find($query->userId);

        if (!$user instanceof AccountUser || $user->getAcademyId() !== $academyId->value()) {
            throw new NotFoundHttpException('Staff no encontrado.');
        }

        return StaffDetailResponse::fromStaffAndUser($staff, $user);
    }
}
