<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\TeamAssignment\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Modules\TeamAssignment\Infrastructure\Persistence\TeamAssignmentRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Support\Database\SchemaResetter;

final class TeamAssignmentRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private TeamAssignmentRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->repository = new TeamAssignmentRepository($doctrine);
        SchemaResetter::create($this->entityManager, [
            $this->entityManager->getClassMetadata(Player::class),
            $this->entityManager->getClassMetadata(Team::class),
            $this->entityManager->getClassMetadata(TeamAssignment::class),
        ]);
    }

    public function testItPersistsAndFindsAssignmentsByPlayer(): void
    {
        $academyId = AcademyId::generate();
        $player = Player::create(PlayerId::generate(), $academyId, 'DNI', 'Juan', 'Pérez', new \DateTimeImmutable('2014-05-18'), '12345678', null, null, null, null, null, null, AuditTrail::create('actor-id'));
        $team = Team::create(TeamId::generate(), $academyId, \App\Modules\Category\Domain\Category\CategoryId::generate(), new Name('Sub-12 A'), AuditTrail::create('actor-id'));

        $this->entityManager->persist($player);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $assignment = TeamAssignment::create(
            TeamAssignmentId::generate(),
            $academyId,
            $player->id(),
            $team->id(),
            new \DateTimeImmutable('2026-07-08'),
            AuditTrail::create('actor-id'),
        );

        $this->repository->save($assignment);

        self::assertCount(1, $this->repository->findAllByPlayer($academyId, $player->id()));
        self::assertSame($assignment->id()->value(), $this->repository->findById($academyId, $assignment->id())?->id()->value());
    }
}
