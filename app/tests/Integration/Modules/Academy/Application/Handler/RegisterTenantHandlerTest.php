<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\RegisterTenantCommand;
use App\Modules\Academy\Application\Dto\TenantSignupInput;
use App\Modules\Academy\Application\Handler\RegisterTenantHandler;
use App\Modules\Academy\Infrastructure\Persistence\AcademyRepository;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Infrastructure\Persistence\TeamRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;

final class RegisterTenantHandlerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private RegisterTenantHandler $handler;
    private AcademyRepository $academyRepository;
    private TeamRepository $teamRepository;
    private string $categoryId;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->academyRepository = new AcademyRepository($doctrine);
        $this->teamRepository = new TeamRepository($doctrine);
        $this->categoryId = CategoryId::generate()->value();
        $this->handler = new RegisterTenantHandler(
            $this->academyRepository,
            new CategoryFinder($this->categoryRepositoryStub()),
            $this->teamRepository,
            $this->entityManager,
            new class implements UserPasswordHasherInterface {
                public function hashPassword(object $user, string $plainPassword): string
                {
                    return 'hashed-'.$plainPassword;
                }

                public function isPasswordValid(object $user, string $plainPassword): bool
                {
                    return true;
                }

                public function needsRehash(object $user): bool
                {
                    return false;
                }
            },
            new class implements MessageBusInterface {
                public function dispatch(object $message, array $stamps = []): Envelope
                {
                    return Envelope::wrap($message);
                }
            },
            'http://localhost:8081',
        );

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $this->dropAllTables();
        $schemaTool->createSchema($metadata);
    }

    public function testItRegistersTenantAcademyAndOwnerUser(): void
    {
        $input = new TenantSignupInput(
            'Academia de Prueba',
            'tenant.test@example.com',
            'Juan Perez',
            'secret123',
            '+51 999 999 999',
            'Colombia',
            'Cundinamarca',
            'Av. Principal 123',
            'Lima',
            $this->categoryId,
            'Sub 12 A',
            true,
            true,
        );

        $response = ($this->handler)(new RegisterTenantCommand($input));
        $payload = $response->toArray();

        self::assertSame('Academia de Prueba', $payload['academy']['name']);
        self::assertSame('tenant.test@example.com', $payload['academy']['contactEmail']);
        self::assertSame('Colombia', $payload['academy']['country']);
        self::assertSame('Cundinamarca', $payload['academy']['department']);
        self::assertSame('signup', $payload['academy']['registrationSource']);
        self::assertSame('tenant.test@example.com', $payload['user']['email']);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $payload['user']['status']);
        self::assertTrue($payload['user']['activationPending']);
        self::assertSame($this->categoryId, $payload['team']['categoryId']);
        self::assertSame('Sub 12 A', $payload['team']['name']);

        $academy = $this->academyRepository->findOneByContactEmail(new Email('tenant.test@example.com'));

        self::assertNotNull($academy);

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->findOneBy([
            'email' => 'tenant.test@example.com',
        ]);

        self::assertInstanceOf(AccountUser::class, $user);
        self::assertSame($academy?->id()->value(), $user?->getAcademyId());
        self::assertSame(AccountUser::ROLE_ACADEMY_ADMIN, $user?->getRole());
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $user?->getStatus());
        self::assertNotNull($user?->getActivationToken());
        self::assertNotNull($user?->getActivationExpiresAt());

        $teams = $this->teamRepository->findAllByAcademy(
            $academy->id(),
            new PaginationQuery(sort: 'auditTrail.createdAt.value')
        );

        self::assertNotEmpty($teams);
        self::assertContains('Sub 12 A', array_map(
            static fn (Team $team): string => $team->name()->value(),
            $teams['items']
        ));
    }

    private function categoryRepositoryStub(): CategoryRepository
    {
        return new class($this->categoryId) implements CategoryRepository {
            public function __construct(
                private readonly string $categoryId,
            ) {
            }

            public function save(Category $category): void
            {
            }

            public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
            {
                if ($categoryId->value() !== $this->categoryId) {
                    return null;
                }

                return Category::create(
                    $categoryId,
                    $academyId,
                    'SUB12',
                    new Name('Sub 12'),
                    new MinimumAge(10),
                    new MaximumAge(12),
                    new Description('Base category'),
                    AuditTrail::create('system'),
                );
            }

            public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category
            {
                return null;
            }

            public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
            {
                return [];
            }
        };
    }

    private function dropAllTables(): void
    {
        $connection = $this->entityManager->getConnection();
        $tables = $connection->fetchFirstColumn('SHOW TABLES');

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($tables as $table) {
            $connection->executeStatement(sprintf('DROP TABLE IF EXISTS `%s`', $table));
        }

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
