<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Team\Presentation\Http;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Team\Domain\Team\Team;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Support\Database\SchemaResetter;
use App\Tests\Support\Database\TestDatabaseKernel;

final class TeamControllerTest extends TestDatabaseKernel
{
    private EntityManagerInterface $entityManager;
    private string $jwtToken;
    private string $categoryId;

    protected function setUp(): void
    {
        $container = $this->bootTestKernel();
        $this->entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        SchemaResetter::reset($this->entityManager, [
            $this->entityManager->getClassMetadata(Academy::class),
            $this->entityManager->getClassMetadata(Category::class),
            $this->entityManager->getClassMetadata(AccountUser::class),
            $this->entityManager->getClassMetadata(Team::class),
        ]);

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email('academy@test.local'),
            new PhoneNumber('+51 999 999 999'),
            'Colombia',
            'Cundinamarca',
            null,
            null,
            null,
            null,
            'signup',
            new Address('Av. Principal 123'),
            new City('Lima'),
            null,
            AuditTrail::create('system'),
        );

        $category = Category::create(
            CategoryId::generate(),
            $academy->id(),
            'SUB16',
            new Name('Sub 16'),
            new MinimumAge(12),
            new MaximumAge(15),
            new Description('Base category'),
            AuditTrail::create('system'),
        );

        $user = new AccountUser();
        $user->setEmail('coach@test.local');
        $user->setPasswordHash('hashed-password');
        $user->setAcademyId($academy->id()->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setFullName('Coach Test');

        $this->entityManager->persist($academy);
        $this->entityManager->persist($category);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->categoryId = $category->id()->value();
        $this->jwtToken = $jwtManager->create($user);
    }

    public function testItCreatesAndListsTeamsForTenant(): void
    {
        $request = Request::create(
            '/api/v1/academy/teams',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'categoryId' => $this->categoryId,
                'name' => 'Sub-16 A',
            ], JSON_THROW_ON_ERROR)
        );

        $response = self::$kernel->handle($request);

        self::assertSame(201, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('Sub-16 A', $payload['data']['name']);
        self::assertSame($this->categoryId, $payload['data']['categoryId']);

        $duplicateResponse = self::$kernel->handle($request);

        self::assertSame(409, $duplicateResponse->getStatusCode());

        $listRequest = Request::create(
            '/api/v1/academy/teams',
            'GET',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
            ]
        );

        $listResponse = self::$kernel->handle($listRequest);

        self::assertSame(200, $listResponse->getStatusCode());

        $listPayload = json_decode($listResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertCount(1, $listPayload['data']);
        self::assertSame('Sub-16 A', $listPayload['data'][0]['name']);
    }
}
