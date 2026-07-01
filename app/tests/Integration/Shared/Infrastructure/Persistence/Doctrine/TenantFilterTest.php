<?php

declare(strict_types=1);

namespace App\Tests\Integration\Shared\Infrastructure\Persistence\Doctrine;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Infrastructure\Persistence\AcademyRepository;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Infrastructure\Persistence\CategoryRepository;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\LogoPath;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class TenantFilterTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private AcademyRepository $academyRepository;
    private CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->academyRepository = new AcademyRepository($doctrine);
        $this->categoryRepository = new CategoryRepository($doctrine);

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $this->entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $this->entityManager->getConnection()->executeStatement('DROP TABLE IF EXISTS players, categories, venues, academies, users');
        $this->entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        $schemaTool->createSchema($metadata);
    }

    public function testTenantFilterAllowsOwnAcademyAndBlocksForeignRecords(): void
    {
        $academyA = Academy::create(
            new AcademyId('019f1111-1111-7111-8111-111111111111'),
            new Name('Academia A'),
            new Email('a@example.com'),
            null,
            null,
            null,
            null,
            AuditTrail::create('019f1111-1111-7111-8111-111111111110'),
        );

        $academyB = Academy::create(
            new AcademyId('019f2222-2222-7222-8222-222222222222'),
            new Name('Academia B'),
            new Email('b@example.com'),
            null,
            null,
            null,
            null,
            AuditTrail::create('019f1111-1111-7111-8111-111111111110'),
        );

        $categoryA = Category::create(
            new CategoryId('019f3333-3333-7333-8333-333333333333'),
            $academyA->id(),
            'SUB_14',
            new Name('Sub 14'),
            new MinimumAge(12),
            new MaximumAge(14),
            new Description('Categoria base'),
            AuditTrail::create('019f1111-1111-7111-8111-111111111110'),
        );

        $categoryB = Category::create(
            new CategoryId('019f4444-4444-7444-8444-444444444444'),
            $academyB->id(),
            'SUB_16',
            new Name('Sub 16'),
            new MinimumAge(14),
            new MaximumAge(16),
            new Description('Categoria base'),
            AuditTrail::create('019f1111-1111-7111-8111-111111111110'),
        );

        $this->entityManager->persist($academyA);
        $this->entityManager->persist($academyB);
        $this->entityManager->persist($categoryA);
        $this->entityManager->persist($categoryB);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->enableTenantFilter($academyA->id()->value());

        $ownAcademy = $this->academyRepository->findById($academyA->id());
        $foreignAcademy = $this->academyRepository->findById($academyB->id());
        $ownCategory = $this->categoryRepository->findById($academyA->id(), $categoryA->id());
        $foreignCategory = $this->categoryRepository->findById($academyA->id(), $categoryB->id());

        self::assertInstanceOf(Academy::class, $ownAcademy);
        self::assertNull($foreignAcademy);
        self::assertInstanceOf(Category::class, $ownCategory);
        self::assertNull($foreignCategory);
    }

    private function enableTenantFilter(string $academyId): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $token = $this->createMock(TokenInterface::class);

        $token->method('hasAttribute')
            ->with('tenant_context')
            ->willReturn(true);

        $token->method('getAttribute')
            ->with('tenant_context')
            ->willReturn([
                'mode' => TenantContext::MODE_TENANT,
                'user_id' => '019f1111-1111-7111-8111-111111111110',
                'academy_id' => $academyId,
                'role' => 'ROLE_ACADEMY_ADMIN',
                'roles' => ['ROLE_ACADEMY_ADMIN'],
            ]);

        $tokenStorage->method('getToken')->willReturn($token);

        $tenantContext = new TenantContext($tokenStorage);

        $filter = $this->entityManager->getFilters()->enable('tenant');
        $filter->setTenantContext($tenantContext);
    }
}
