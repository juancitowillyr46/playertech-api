<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Guardian\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Command\ActivateLegalGuardianCommand;
use App\Modules\Guardian\Application\Command\InactivateLegalGuardianCommand;
use App\Modules\Guardian\Application\Command\UpdateLegalGuardianCommand;
use App\Modules\Guardian\Application\Dto\UpdateLegalGuardianInput;
use App\Modules\Guardian\Application\Handler\ActivateLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\InactivateLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\UpdateLegalGuardianHandler;
use App\Modules\Guardian\Domain\Exception\LegalGuardianAlreadyExistsException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class GuardianLifecycleHandlersTest extends TestCase
{
    public function testItUpdatesGuardianWithinTheAcademy(): void
    {
        $academyId = AcademyId::generate();
        $guardianId = LegalGuardianId::generate();

        $repository = new InMemoryGuardianRepository(
            LegalGuardian::create(
                $guardianId,
                $academyId,
                'Maria',
                'Lopez',
                '+573000000000',
                'maria@example.com',
                'CC',
                '12345678',
                null,
                'Madre',
                AuditTrail::create('actor-id'),
            )
        );

        $handler = new UpdateLegalGuardianHandler($repository);

        $response = $handler(new UpdateLegalGuardianCommand(
            'actor-id',
            $academyId,
            $guardianId->value(),
            new UpdateLegalGuardianInput(
                'Maria',
                'Gomez',
                '+573111111111',
                'maria.gomez@example.com',
                'CC',
                '12345678',
                'Calle 10 # 20-30',
                'Madre',
            ),
        ));

        self::assertSame('Gomez', $response->toArray()['lastName']);
        self::assertSame('maria.gomez@example.com', $repository->findById($academyId, $guardianId)?->email());
        self::assertSame('Calle 10 # 20-30', $repository->findById($academyId, $guardianId)?->address());
    }

    public function testItRejectsARepeatedEmailInTheSameAcademyOnUpdate(): void
    {
        $academyId = AcademyId::generate();
        $guardianId = LegalGuardianId::generate();
        $otherGuardianId = LegalGuardianId::generate();

        $repository = new InMemoryGuardianRepository(
            LegalGuardian::create(
                $guardianId,
                $academyId,
                'Maria',
                'Lopez',
                '+573000000000',
                'maria@example.com',
                'CC',
                '12345678',
                null,
                'Madre',
                AuditTrail::create('actor-id'),
            ),
            LegalGuardian::create(
                $otherGuardianId,
                $academyId,
                'Ana',
                'Perez',
                '+573000000001',
                'ana@example.com',
                'CC',
                '87654321',
                null,
                'Madre',
                AuditTrail::create('actor-id'),
            ),
        );

        $handler = new UpdateLegalGuardianHandler($repository);

        $this->expectException(LegalGuardianAlreadyExistsException::class);

        $handler(new UpdateLegalGuardianCommand(
            'actor-id',
            $academyId,
            $guardianId->value(),
            new UpdateLegalGuardianInput(
                'Maria',
                'Lopez',
                '+573000000000',
                'ana@example.com',
                'CC',
                '12345678',
                null,
                'Madre',
            ),
        ));
    }

    public function testItInactivatesAndReactivatesGuardianWithinTheAcademy(): void
    {
        $academyId = AcademyId::generate();
        $guardianId = LegalGuardianId::generate();

        $repository = new InMemoryGuardianRepository(
            LegalGuardian::create(
                $guardianId,
                $academyId,
                'Maria',
                'Lopez',
                '+573000000000',
                'maria@example.com',
                'CC',
                '12345678',
                null,
                'Madre',
                AuditTrail::create('actor-id'),
            )
        );

        $inactivateHandler = new InactivateLegalGuardianHandler($repository);
        $activateHandler = new ActivateLegalGuardianHandler($repository);

        $inactivateHandler(new InactivateLegalGuardianCommand('actor-id', $academyId, $guardianId->value()));
        self::assertTrue($repository->findById($academyId, $guardianId)?->status()->isInactive());

        $activateHandler(new ActivateLegalGuardianCommand('actor-id', $academyId, $guardianId->value()));
        self::assertTrue($repository->findById($academyId, $guardianId)?->status()->isActive());
    }
}

final class InMemoryGuardianRepository implements LegalGuardianRepository
{
    /** @var array<string, LegalGuardian> */
    public array $items = [];

    public function __construct(LegalGuardian ...$guardians)
    {
        foreach ($guardians as $guardian) {
            $this->save($guardian);
        }
    }

    public function save(LegalGuardian $guardian): void
    {
        $this->items[$guardian->id()->value()] = $guardian;
    }

    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian
    {
        $guardian = $this->items[$guardianId->value()] ?? null;

        if (null === $guardian || !$guardian->academyId()->equals($academyId)) {
            return null;
        }

        return $guardian;
    }

    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian
    {
        foreach ($this->items as $guardian) {
            if (
                $guardian->academyId()->equals($academyId)
                && null !== $guardian->email()
                && mb_strtolower($guardian->email()) === mb_strtolower(trim($email))
            ) {
                return $guardian;
            }
        }

        return null;
    }

    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $firstName = null,
        ?string $lastName = null,
    ): array
    {
        $items = array_values(array_filter(
            $this->items,
            static fn (LegalGuardian $guardian): bool => $guardian->academyId()->equals($academyId)
        ));

        return [
            'items' => $items,
            'total' => count($items),
        ];
    }
}
