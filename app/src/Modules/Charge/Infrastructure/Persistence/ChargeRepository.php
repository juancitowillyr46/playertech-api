<?php
declare(strict_types=1);
namespace App\Modules\Charge\Infrastructure\Persistence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Charge\Domain\Charge\ChargeRepository as Contract;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
final class ChargeRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, Charge::class); }
    public function save(Charge $charge): void { $this->getEntityManager()->persist($charge); $this->getEntityManager()->flush(); }
    public function findById(AcademyId $academyId, ChargeId $chargeId): ?Charge { return $this->createQueryBuilder('c')->where('c.id = :id')->andWhere('c.academyId = :academyId')->andWhere('c.deletedAt IS NULL')->setParameter('id', $chargeId->value())->setParameter('academyId', $academyId->value())->getQuery()->getOneOrNullResult(); }
    public function findPendingByPlayer(AcademyId $academyId, PlayerId $playerId): array { return $this->createQueryBuilder('c')->where('c.academyId = :academyId')->andWhere('c.playerId = :playerId')->andWhere('c.status = :status')->andWhere('c.deletedAt IS NULL')->setParameter('academyId', $academyId->value())->setParameter('playerId', $playerId->value())->setParameter('status', 'PENDING')->orderBy('c.dueDate', 'ASC')->getQuery()->getResult(); }
    public function findPendingByAcademy(AcademyId $academyId, PaginationQuery $pagination): array {
        $qb = $this->createQueryBuilder('c')->where('c.academyId = :academyId')->andWhere('c.status = :status')->andWhere('c.deletedAt IS NULL')->setParameter('academyId', $academyId->value())->setParameter('status', 'PENDING')->orderBy(sprintf('c.%s', $pagination->sort), $pagination->direction);
        $total = (int) (clone $qb)->select('COUNT(c.id)')->getQuery()->getSingleScalarResult();
        $items = $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult();
        return ['items' => $items, 'total' => $total];
    }
}
