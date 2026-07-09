<?php
declare(strict_types=1);
namespace App\Modules\Staff\Infrastructure\Persistence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentRepository as Contract;
use App\Modules\Team\Domain\Team\TeamId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
final class TeamStaffAssignmentRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, TeamStaffAssignment::class); }
    public function save(TeamStaffAssignment $assignment): void { $this->getEntityManager()->persist($assignment); $this->getEntityManager()->flush(); }
    public function findByStaffAndTeam(AcademyId $academyId, StaffId $staffId, TeamId $teamId): ?TeamStaffAssignment { return $this->createQueryBuilder('a')->where('a.academyId = :academyId')->andWhere('a.staffId = :staffId')->andWhere('a.teamId = :teamId')->andWhere('a.deletedAt IS NULL')->setParameter('academyId',$academyId->value())->setParameter('staffId',$staffId->value())->setParameter('teamId',$teamId->value())->getQuery()->getOneOrNullResult(); }
    public function findById(AcademyId $academyId, \App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId $assignmentId): ?TeamStaffAssignment { return $this->createQueryBuilder('a')->where('a.academyId = :academyId')->andWhere('a.id = :id')->andWhere('a.deletedAt IS NULL')->setParameter('academyId',$academyId->value())->setParameter('id',$assignmentId->value())->getQuery()->getOneOrNullResult(); }
    public function findAllByTeam(AcademyId $academyId, TeamId $teamId): array { return $this->createQueryBuilder('a')->where('a.academyId = :academyId')->andWhere('a.teamId = :teamId')->andWhere('a.deletedAt IS NULL')->setParameter('academyId',$academyId->value())->setParameter('teamId',$teamId->value())->getQuery()->getResult(); }
}
