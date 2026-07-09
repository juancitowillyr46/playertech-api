<?php
declare(strict_types=1);
namespace App\Modules\Payment\Infrastructure\Persistence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocation;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationRepository as Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
final class PaymentAllocationRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, PaymentAllocation::class); }
    public function save(PaymentAllocation $allocation): void { $this->getEntityManager()->persist($allocation); $this->getEntityManager()->flush(); }
    public function findByPaymentAndCharge(AcademyId $academyId, string $paymentId, string $chargeId): ?PaymentAllocation { return $this->createQueryBuilder('a')->where('a.academyId = :academyId')->andWhere('a.paymentId = :paymentId')->andWhere('a.chargeId = :chargeId')->andWhere('a.deletedAt IS NULL')->setParameter('academyId', $academyId->value())->setParameter('paymentId', $paymentId)->setParameter('chargeId', $chargeId)->getQuery()->getOneOrNullResult(); }
}
