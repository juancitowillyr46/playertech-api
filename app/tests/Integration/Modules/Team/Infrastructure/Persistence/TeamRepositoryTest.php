<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Team\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Infrastructure\Persistence\TeamRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class TeamRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private TeamRepository $teamRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->teamRepository = new TeamRepository($doctrine);

        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('DROP TABLE IF EXISTS teams');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(Team::class),
        ]);
    }

    public function testItPersistsAndLoadsTeamsByAcademy(): void
    {
        $academyId = AcademyId::generate();
        $categoryId = CategoryId::generate();

        $team = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Sub-16'),
            AuditTrail::create('actor-id'),
        );

        $this->teamRepository->save($team);

        $found = $this->teamRepository->findById($academyId, $team->id());

        self::assertNotNull($found);
        self::assertSame($team->id()->value(), $found?->id()->value());
        self::assertCount(1, $this->teamRepository->findAllByAcademy($academyId));
        self::assertSame(
            $team->id()->value(),
            $this->teamRepository
                ->findOneByAcademyCategoryAndName($academyId, $categoryId, new Name('Sub-16'))
                ?->id()
                ->value()
        );
    }
}
