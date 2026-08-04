<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Guardian\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Handler\ListGuardianOptionsHandler;
use App\Modules\Guardian\Application\Query\ListGuardianOptionsQuery;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class ListGuardianOptionsHandlerTest extends TestCase
{
    public function testItListsGuardiansForAutocomplete(): void
    {
        $academyId = AcademyId::generate();
        $guardianRepository = new InMemoryGuardianRepository(
            LegalGuardian::create(
                LegalGuardianId::generate(),
                $academyId,
                'Juan',
                'Rodas',
                '+573213755187',
                'juan@example.com',
                'CC',
                '1088329031',
                null,
                'Madre',
                AuditTrail::create('actor'),
            ),
            LegalGuardian::create(
                LegalGuardianId::generate(),
                $academyId,
                'Carlos',
                'Perez',
                '+573213755188',
                'carlos@example.com',
                'CE',
                '1088329032',
                null,
                'Padre',
                AuditTrail::create('actor'),
            ),
        );

        $handler = new ListGuardianOptionsHandler($guardianRepository);
        $result = $handler(new ListGuardianOptionsQuery($academyId, 'ju'));

        self::assertCount(1, $result);
        self::assertSame('Juan', $result[0]->toArray()['firstName']);
        self::assertSame('Rodas', $result[0]->toArray()['lastName']);
        self::assertSame('1088329031', $result[0]->toArray()['documentNumber']);
        self::assertSame('Cédula de ciudadanía', $result[0]->toArray()['documentTypeName']);
        self::assertSame('Madre', $result[0]->toArray()['relationshipName']);
    }
}

final class InMemoryGuardianRepository implements LegalGuardianRepository
{
    /** @var array<string, LegalGuardian> */
    private array $items = [];

    public function __construct(LegalGuardian ...$guardians)
    {
        foreach ($guardians as $guardian) {
            $this->items[$guardian->id()->value()] = $guardian;
        }
    }

    public function save(LegalGuardian $guardian): void
    {
        $this->items[$guardian->id()->value()] = $guardian;
    }

    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian
    {
        $guardian = $this->items[$guardianId->value()] ?? null;

        return null !== $guardian && $guardian->academyId()->equals($academyId) ? $guardian : null;
    }

    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian
    {
        return null;
    }

    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $documentNumber = null,
        ?string $documentType = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $fullName = null,
    ): array {
        $items = array_values(array_filter(
            $this->items,
            static function (LegalGuardian $guardian) use ($academyId, $documentNumber, $documentType, $firstName, $lastName, $fullName): bool {
                if (!$guardian->academyId()->equals($academyId)) {
                    return false;
                }

                if (null !== $documentNumber && '' !== trim($documentNumber) && !str_contains(mb_strtolower($guardian->documentNumber() ?? ''), mb_strtolower(trim($documentNumber)))) {
                    return false;
                }

                if (null !== $documentType && '' !== trim($documentType) && mb_strtoupper($guardian->documentType() ?? '') !== mb_strtoupper(trim($documentType))) {
                    return false;
                }

                if (null !== $firstName && '' !== trim($firstName) && !str_contains(mb_strtolower($guardian->firstName()), mb_strtolower(trim($firstName)))) {
                    return false;
                }

                if (null !== $lastName && '' !== trim($lastName) && !str_contains(mb_strtolower($guardian->lastName()), mb_strtolower(trim($lastName)))) {
                    return false;
                }

                if (null !== $fullName && '' !== trim($fullName)) {
                    $needle = mb_strtolower(trim($fullName));
                    $full = mb_strtolower($guardian->firstName().' '.$guardian->lastName());
                    if (!str_contains($full, $needle) && !str_contains(mb_strtolower($guardian->firstName()), $needle) && !str_contains(mb_strtolower($guardian->lastName()), $needle)) {
                        return false;
                    }
                }

                return true;
            }
        ));

        return ['items' => $items, 'total' => count($items)];
    }
}
