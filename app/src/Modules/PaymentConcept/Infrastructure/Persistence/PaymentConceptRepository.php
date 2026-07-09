<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository as Contract;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PaymentConceptRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, PaymentConcept::class); }
    public function save(PaymentConcept $paymentConcept): void { $this->getEntityManager()->persist($paymentConcept); $this->getEntityManager()->flush(); }
    public function findById(AcademyId $academyId, PaymentConceptId $paymentConceptId): ?PaymentConcept { return $this->createQueryBuilder('pc')->where('pc.id = :id')->andWhere('pc.academyId = :academyId')->andWhere('pc.deletedAt IS NULL')->setParameter('id', $paymentConceptId->value())->setParameter('academyId', $academyId->value())->getQuery()->getOneOrNullResult(); }
    public function findByCode(AcademyId $academyId, string $code): ?PaymentConcept { return $this->createQueryBuilder('pc')->where('pc.academyId = :academyId')->andWhere('pc.code = :code')->andWhere('pc.deletedAt IS NULL')->setParameter('academyId', $academyId->value())->setParameter('code', strtoupper(trim($code)))->getQuery()->getOneOrNullResult(); }
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array {
        $qb = $this->createQueryBuilder('pc')->where('pc.academyId = :academyId')->andWhere('pc.deletedAt IS NULL')->setParameter('academyId', $academyId->value())->orderBy(sprintf('pc.%s', $pagination->sort), $pagination->direction);
        $total = (int) (clone $qb)->select('COUNT(pc.id)')->getQuery()->getSingleScalarResult();
        $items = $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult();
        return ['items' => $items, 'total' => $total];
    }
}
