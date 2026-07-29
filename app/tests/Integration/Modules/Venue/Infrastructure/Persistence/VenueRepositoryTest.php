<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Venue\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Infrastructure\Persistence\VenueRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Tests\Support\Database\SchemaResetter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class VenueRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private VenueRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->repository = new VenueRepository(self::$kernel->getContainer()->get('doctrine'));

        SchemaResetter::reset($this->entityManager, $this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testItFindsVenueByAcademyAndName(): void
    {
        $academyId = AcademyId::generate();
        $venue = Venue::create(
            VenueId::generate(),
            $academyId,
            new Name('Sede Norte'),
            new Address('Calle 123'),
            new City('Pereira'),
            'Colombia',
            'Risaralda',
            new PhoneNumber('+573001112233'),
            null,
            false,
            AuditTrail::create('system'),
        );

        $this->repository->save($venue);

        $found = $this->repository->findByAcademyAndName($academyId, 'Sede Norte');

        self::assertNotNull($found);
        self::assertSame($venue->id()->value(), $found?->id()->value());
    }
}
