<?php
declare(strict_types=1);
namespace App\Modules\Payment\Infrastructure\Persistence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository as Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
final class PaymentRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, Payment::class); }
    public function save(Payment $payment): void { $this->getEntityManager()->persist($payment); $this->getEntityManager()->flush(); }
    public function findById(AcademyId $academyId, PaymentId $paymentId): ?Payment { return $this->createQueryBuilder('p')->where('p.id = :id')->andWhere('p.academyId = :academyId')->andWhere('p.deletedAt IS NULL')->setParameter('id', $paymentId->value())->setParameter('academyId', $academyId->value())->getQuery()->getOneOrNullResult(); }
    public function findAllByAcademy(AcademyId $academyId): array { return $this->createQueryBuilder('p')->where('p.academyId = :academyId')->andWhere('p.deletedAt IS NULL')->setParameter('academyId', $academyId->value())->getQuery()->getResult(); }
}
