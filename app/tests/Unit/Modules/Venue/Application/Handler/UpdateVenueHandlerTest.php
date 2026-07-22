<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Venue\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Application\Command\UpdateVenueCommand;
use App\Modules\Venue\Application\Dto\UpdateVenueInput;
use App\Modules\Venue\Application\Handler\UpdateVenueHandler;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Notes;
use App\Shared\Domain\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

final class UpdateVenueHandlerTest extends TestCase
{
    public function testItUpdatesVenueWithAllEditableFields(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $venue = Venue::create(
            new VenueId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            new Name('Sede A'),
            new Address('Dirección antigua'),
            new City('Pereira'),
            'Colombia',
            'Risaralda',
            new PhoneNumber('+573000000000'),
            null,
            false,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );

        $repository = new UpdateVenueInMemoryVenueRepository($venue);

        $handler = new UpdateVenueHandler($repository, new \App\Modules\Venue\Application\Services\VenueFinder($repository));

        $response = $handler(new UpdateVenueCommand(
            'actor-id',
            $academyId->value(),
            $venue->id()->value(),
            new UpdateVenueInput(
                'Sede A Renovada',
                '45678',
                'Pereira',
                'Colombia',
                'Risaralda',
                '+573125953354',
                'Hello'
            )
        ));

        self::assertInstanceOf(VenueResponse::class, $response);
        self::assertSame('Sede A Renovada', $response->toArray()['name']);
        self::assertSame('45678', $response->toArray()['address']);
        self::assertSame('Pereira', $response->toArray()['city']);
        self::assertSame('Colombia', $response->toArray()['country']);
        self::assertSame('Risaralda', $response->toArray()['department']);
        self::assertSame('+573125953354', $response->toArray()['phone']);
        self::assertSame('Hello', $response->toArray()['notes']);
    }
}

final class UpdateVenueInMemoryVenueRepository implements VenueRepository
{
    public function __construct(
        private Venue $venue,
    ) {
    }

    public function save(Venue $venue): void
    {
        $this->venue = $venue;
    }

    public function findById(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        if (
            $this->venue->academyId()->value() === $academyId->value()
            && $this->venue->id()->value() === $venueId->value()
        ) {
            return $this->venue;
        }

        return null;
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        return [
            'items' => [$this->venue],
            'total' => 1,
        ];
    }

    public function findByAcademyAndId(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        return $this->findById($academyId, $venueId);
    }
}
