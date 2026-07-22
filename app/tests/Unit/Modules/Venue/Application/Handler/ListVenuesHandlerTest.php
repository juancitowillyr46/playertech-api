<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Venue\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Application\Handler\ListVenuesHandler;
use App\Modules\Venue\Application\Query\ListVenuesQuery;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Modules\Venue\Domain\Venue\VenueStatus;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Shared\Domain\ValueObject\Notes;
use PHPUnit\Framework\TestCase;

final class ListVenuesHandlerTest extends TestCase
{
    public function testItListsVenuesForTheGivenAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $repository = new InMemoryVenueRepository();

        $repository->save(Venue::create(
            VenueId::generate(),
            $academyId,
            new Name('Sede Principal'),
            new Address('Av. Principal 123'),
            new City('Lima'),
            'Colombia',
            'Cundinamarca',
            new PhoneNumber('+51 999 999 999'),
            new Notes('Sede operativa'),
            true,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ListVenuesHandler($repository);
        $venues = $handler(new ListVenuesQuery($academyId, new PaginationQuery()));

        self::assertCount(1, $venues->items);
        self::assertSame('Sede Principal', $venues->items[0]->toArray()['name']);
        self::assertSame('Av. Principal 123', $venues->items[0]->toArray()['address']);
        self::assertSame('Lima', $venues->items[0]->toArray()['city']);
        self::assertSame('+51999999999', $venues->items[0]->toArray()['phone']);
        self::assertSame(VenueStatus::active()->value(), $venues->items[0]->toArray()['status']);
    }
}

final class InMemoryVenueRepository implements VenueRepository
{
    /** @var Venue[] */
    private array $venues = [];

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

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $items = array_values(array_filter(
            $this->venues,
            static fn (Venue $venue): bool => $venue->academyId()->value() === $academyId->value()
        ));

        return [
            'items' => $items,
            'total' => count($items),
        ];
    }

    public function findByAcademyAndId(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        return $this->findById($academyId, $venueId);
    }
}
