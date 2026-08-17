<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use App\Modules\Player\Application\Command\CreatePlayerCommand;
use App\Modules\Player\Application\Dto\CreatePlayerInput;
use App\Modules\Player\Application\Handler\CreatePlayerHandler;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;
use PHPUnit\Framework\TestCase;

final class CreatePlayerHandlerTest extends TestCase
{
    public function testItCreatesPlayerWithinAcademyContext(): void
    {
        $repository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $handler = new CreatePlayerHandler($repository, new CategoryFinder($categoryRepository));

        $response = $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', '2014-05-18', '12345678'),
        ));

        self::assertSame('DNI', $response->toArray()['documentType']);
        self::assertSame('Juan', $response->toArray()['firstName']);
        self::assertSame('Pérez', $response->toArray()['lastName']);
        self::assertSame('2014-05-18', $response->toArray()['birthDate']);
        self::assertSame('12345678', $response->toArray()['documentNumber']);
        self::assertSame('ACTIVE', $response->toArray()['status']);
        self::assertCount(1, $repository->players);
    }

    public function testItCreatesPlayerWithFallbackBirthDateWhenMissing(): void
    {
        $repository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $handler = new CreatePlayerHandler($repository, new CategoryFinder($categoryRepository));

        $response = $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', null, '12345678'),
        ));

        self::assertSame((new \DateTimeImmutable('today'))->format('Y-m-d'), $response->toArray()['birthDate']);
        self::assertCount(1, $repository->players);
    }

    public function testItRejectsDuplicateDocumentWithinTheSameAcademy(): void
    {
        $repository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $handler = new CreatePlayerHandler($repository, new CategoryFinder($categoryRepository));

        $command = new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', '2014-05-18', '12345678'),
        );

        $handler($command);

        $this->expectException(PlayerAlreadyExistsException::class);

        $handler($command);
    }

    public function testItRejectsDuplicateEmailWithinTheSameAcademy(): void
    {
        $repository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $handler = new CreatePlayerHandler($repository, new CategoryFinder($categoryRepository));

        $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', '2014-05-18', '12345678', null, 'juan@example.com', '3125953354'),
        ));

        $this->expectException(PlayerAlreadyExistsException::class);
        $this->expectExceptionMessage('El correo electrónico ya existe para esta academia.');

        $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Pedro', 'López', '2014-05-18', '87654321', null, 'juan@example.com', '3001112233'),
        ));
    }

    public function testItAllowsDuplicatedPhoneWithinTheSameAcademy(): void
    {
        $repository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $handler = new CreatePlayerHandler($repository, new CategoryFinder($categoryRepository));

        $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', '2014-05-18', '12345678', null, 'juan@example.com', '3125953354'),
        ));

        $response = $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Pedro', 'López', '2014-05-18', '87654321', null, 'pedro@example.com', '3125953354'),
        ));

        self::assertSame('+573125953354', $response->toArray()['phone']);
        self::assertCount(2, $repository->players);
    }

    public function testItPersistsCategoryIdWhenProvided(): void
    {
        $repository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d70');

        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB-14',
            new Name('Sub 14'),
            new MinimumAge(13),
            new MaximumAge(14),
            new Description('Categoria formativa'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new CreatePlayerHandler($repository, new CategoryFinder($categoryRepository));

        $response = $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            new CreatePlayerInput('CC', 'Juan', 'Castaño', null, '1088329016', $categoryId->value()),
        ));

        self::assertSame($categoryId->value(), $response->toArray()['categoryId']);
    }
}
