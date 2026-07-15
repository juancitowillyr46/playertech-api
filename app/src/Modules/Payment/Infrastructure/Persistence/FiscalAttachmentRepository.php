<?php

declare(strict_types=1);

namespace App\Modules\Payment\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachment;
use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachmentRepository as Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class FiscalAttachmentRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FiscalAttachment::class);
    }

    public function save(FiscalAttachment $attachment): void
    {
        $this->getEntityManager()->persist($attachment);
        $this->getEntityManager()->flush();
    }

    public function findAllByAcademy(AcademyId $academyId): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.academyId = :academyId')
            ->andWhere('d.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getResult();
    }
}
