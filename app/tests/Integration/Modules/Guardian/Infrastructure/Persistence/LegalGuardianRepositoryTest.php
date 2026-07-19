<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Guardian\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Infrastructure\Persistence\LegalGuardianRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Support\Database\SchemaResetter;

final class LegalGuardianRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private LegalGuardianRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->repository = new LegalGuardianRepository($doctrine);
        SchemaResetter::reset($this->entityManager, [
            $this->entityManager->getClassMetadata(LegalGuardian::class),
        ]);
    }

    public function testItPersistsAndFindsGuardiansByAcademy(): void
    {
        $academyId = AcademyId::generate();

        $guardian = LegalGuardian::create(
            LegalGuardianId::generate(),
            $academyId,
            'Maria',
            'Lopez',
            '+51 999 111 222',
            'maria@example.com',
            'Madre',
            AuditTrail::create('actor-id')
        );

        $this->repository->save($guardian);

        $found = $this->repository->findById($academyId, $guardian->id());

        self::assertNotNull($found);
        self::assertSame($guardian->id()->value(), $found?->id()->value());
        self::assertSame('maria@example.com', $this->repository->findOneByEmail($academyId, 'maria@example.com')?->email());
        self::assertSame('Madre', $found?->relationship());
        self::assertCount(1, $this->repository->findAllByAcademy($academyId, new PaginationQuery(1, 20, 'auditTrail.createdAt.value', 'DESC'))['items']);
    }
}
