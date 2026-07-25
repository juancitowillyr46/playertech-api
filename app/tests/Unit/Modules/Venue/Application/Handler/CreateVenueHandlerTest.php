<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Venue\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Application\Command\CreateVenueCommand;
use App\Modules\Venue\Application\Dto\CreateVenueInput;
use App\Modules\Venue\Application\Handler\CreateVenueHandler;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Exception\VenueAlreadyExistsException;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

final class CreateVenueHandlerTest extends TestCase
{
    public function testItCreatesVenueWithoutNotes(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $repository = new CreateVenueInMemoryRepository();
        $handler = new CreateVenueHandler($repository);

        $response = $handler(new CreateVenueCommand(
            'actor-id',
            $academyId->value(),
            new CreateVenueInput(
                'Sede A',
                'Dirección ABC',
                'Pereira',
                'Colombia',
                'Risaralda',
                '+573125953354',
                null,
            )
        ));

        self::assertInstanceOf(VenueResponse::class, $response);
        self::assertSame('Sede A', $response->toArray()['name']);
        self::assertSame('Dirección ABC', $response->toArray()['address']);
        self::assertNull($response->toArray()['notes']);
    }

    public function testItRejectsDuplicatedVenueNameWithinSameAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $existing = Venue::create(
            new VenueId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            new Name('Sede A'),
            new Address('Dirección ABC'),
            new City('Pereira'),
            'Colombia',
            'Risaralda',
            new PhoneNumber('+573125953354'),
            null,
            false,
            AuditTrail::create('actor-id'),
        );

        $repository = new CreateVenueInMemoryRepository($existing);
        $handler = new CreateVenueHandler($repository);

        $this->expectException(VenueAlreadyExistsException::class);

        $handler(new CreateVenueCommand(
            'actor-id',
            $academyId->value(),
            new CreateVenueInput(
                'Sede A',
                'Otra Dirección',
                'Pereira',
                'Colombia',
                'Risaralda',
                '+573125953355',
                null,
            )
        ));
    }
}

final class CreateVenueInMemoryRepository implements VenueRepository
{
    /** @var Venue[] */
    private array $venues = [];

    public function __construct(Venue ...$venues)
    {
        foreach ($venues as $venue) {
            $this->venues[] = $venue;
        }
    }

    public function save(Venue $venue): void
    {
        $this->venues[] = $venue;
    }

    public function findById(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        foreach ($this->venues as $venue) {
            if ($venue->academyId()->value() === $academyId->value() && $venue->id()->value() === $venueId->value()) {
                return $venue;
            }
        }

        return null;
    }

    public function findByAcademyAndName(AcademyId $academyId, string $name): ?Venue
    {
        foreach ($this->venues as $venue) {
            if ($venue->academyId()->value() === $academyId->value() && $venue->name()->value() === $name) {
                return $venue;
            }
        }

        return null;
    }

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        return [
            'items' => array_values(array_filter(
                $this->venues,
                static fn (Venue $venue): bool => $venue->academyId()->value() === $academyId->value()
            )),
            'total' => count($this->venues),
        ];
    }

    public function findByAcademyAndId(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        return $this->findById($academyId, $venueId);
    }
}
