<?php
declare(strict_types=1);
namespace App\Modules\Payment\Infrastructure\Persistence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\PaymentEvidence\PaymentEvidence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
final class PaymentEvidenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry){ parent::__construct($registry, PaymentEvidence::class); }
    public function save(PaymentEvidence $evidence): void { $this->getEntityManager()->persist($evidence); $this->getEntityManager()->flush(); }
}
