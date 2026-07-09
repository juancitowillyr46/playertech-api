<?php
declare(strict_types=1);
namespace App\Modules\Staff\Infrastructure\Persistence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository as Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
final class StaffRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, Staff::class); }
    public function save(Staff $staff): void { $this->getEntityManager()->persist($staff); $this->getEntityManager()->flush(); }
    public function findByUserId(AcademyId $academyId, string $userId): ?Staff { return $this->createQueryBuilder('staff')->where('staff.academyId = :academyId')->andWhere('staff.userId = :userId')->andWhere('staff.deletedAt IS NULL')->setParameter('academyId',$academyId->value())->setParameter('userId',$userId)->getQuery()->getOneOrNullResult(); }
    public function findById(AcademyId $academyId, StaffId $staffId): ?Staff { return $this->createQueryBuilder('staff')->where('staff.academyId = :academyId')->andWhere('staff.id = :staffId')->andWhere('staff.deletedAt IS NULL')->setParameter('academyId',$academyId->value())->setParameter('staffId',$staffId->value())->getQuery()->getOneOrNullResult(); }
    public function findAllByAcademy(AcademyId $academyId): array { return $this->createQueryBuilder('staff')->where('staff.academyId = :academyId')->andWhere('staff.deletedAt IS NULL')->setParameter('academyId',$academyId->value())->getQuery()->getResult(); }
}
