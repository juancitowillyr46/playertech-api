<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Command\RegisterStaffMemberCommand;
use App\Modules\Staff\Application\Response\StaffResponse;
use App\Modules\Staff\Domain\Exception\StaffAlreadyExistsException;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
final readonly class RegisterStaffMemberHandler
{
    public function __construct(private StaffRepository $staffRepository, private EntityManagerInterface $entityManager) {}
    public function __invoke(RegisterStaffMemberCommand $command): StaffResponse
    {
        $academyId = new AcademyId($command->academyId);
        if (null !== $this->staffRepository->findByUserId($academyId, $command->userId)) { throw new StaffAlreadyExistsException(); }
        $user = $this->entityManager->getRepository(AccountUser::class)->find($command->userId);
        if (!$user instanceof AccountUser || $user->getAcademyId() !== $academyId->value()) { throw new StaffAlreadyExistsException(); }
        $staff = Staff::create(StaffId::generate(), $academyId, $command->userId, AuditTrail::create($command->actorId));
        $this->staffRepository->save($staff);
        return StaffResponse::fromStaff($staff);
    }
}
