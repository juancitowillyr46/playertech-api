<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Player\Application\Command\ImportPlayersCommand;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\ValueObject\AuditTrail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final readonly class ImportPlayersHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return PlayerResponse[]
     */
    public function __invoke(ImportPlayersCommand $command): array
    {
        $academyId = new AcademyId($command->academyId);
        $rows = $this->readRows($command->file->getPathname());
        $violations = new ConstraintViolationList();
        $players = [];
        $documentNumbers = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $firstName = trim((string) ($row['first_name'] ?? ''));
            $lastName = trim((string) ($row['last_name'] ?? ''));
            $birthDate = trim((string) ($row['birth_date'] ?? ''));
            $documentNumber = trim((string) ($row['document_number'] ?? ''));
            $categoryKey = trim((string) ($row['category_key'] ?? ''));
            $category = null;

            if ('' === $firstName) {
                $violations->add($this->violation("rows[$line].first_name", 'El campo "first_name" es obligatorio.'));
            }

            if ('' === $lastName) {
                $violations->add($this->violation("rows[$line].last_name", 'El campo "last_name" es obligatorio.'));
            }

            $parsedBirthDate = \DateTimeImmutable::createFromFormat('Y-m-d', $birthDate);
            if (false === $parsedBirthDate || $parsedBirthDate->format('Y-m-d') !== $birthDate) {
                $violations->add($this->violation("rows[$line].birth_date", 'El campo "birth_date" debe tener formato Y-m-d.'));
            }

            if ('' === $documentNumber) {
                $violations->add($this->violation("rows[$line].document_number", 'El campo "document_number" es obligatorio.'));
            }

            if ('' === $categoryKey) {
                $violations->add($this->violation("rows[$line].category_key", 'El campo "category_key" es obligatorio.'));
            } else {
                $category = $this->categoryRepository->findByCategoryKey($academyId, $categoryKey);
                if (null === $category) {
                    $violations->add($this->violation("rows[$line].category_key", 'La categoría no existe en la academia.'));
                }
            }

            if (isset($documentNumbers[$documentNumber])) {
                $violations->add($this->violation("rows[$line].document_number", 'El documento está duplicado dentro del archivo.'));
            }

            if (null !== $this->playerRepository->findOneByDocumentNumber($academyId, $documentNumber)) {
                $violations->add($this->violation("rows[$line].document_number", 'El documento ya existe para esta academia.'));
            }

            $documentNumbers[$documentNumber] = true;

            if ($violations->count() > 0) {
                continue;
            }

            $players[] = Player::create(
                PlayerId::generate(),
                $academyId,
                $firstName,
                $lastName,
                $parsedBirthDate,
                $documentNumber,
                $category->id(),
                null,
                AuditTrail::create($command->actorId),
            );
        }

        if (0 < $violations->count()) {
            throw new ValidationException($violations);
        }

        foreach ($players as $player) {
            $this->playerRepository->save($player);
        }

        return array_map(
            static fn (Player $player): PlayerResponse => PlayerResponse::fromPlayer($player),
            $players
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function readRows(string $path): array
    {
        try {
            $spreadsheet = IOFactory::load($path);
        } catch (ReaderException $exception) {
            throw new ValidationException(new ConstraintViolationList([
                $this->violation('file', sprintf('No se pudo leer el archivo Excel: %s', $exception->getMessage())),
            ]));
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if ([] === $rows) {
            throw new ValidationException(new ConstraintViolationList([
                $this->violation('file', 'El archivo Excel está vacío.'),
            ]));
        }

        $header = array_map('strtolower', array_map('trim', array_values(array_shift($rows))));
        $expected = ['first_name', 'last_name', 'birth_date', 'document_number', 'category_key'];

        if ($header !== $expected) {
            throw new ValidationException(new ConstraintViolationList([
                $this->violation('file', 'La plantilla del Excel no coincide con el formato esperado.'),
            ]));
        }

        return array_values(array_filter(
            array_map(
                static fn (array $row): array => array_combine($expected, array_values($row)) ?: [],
                $rows
            ),
            static fn (array $row): bool => [] !== array_filter($row, static fn ($value): bool => null !== $value && '' !== trim((string) $value))
        ));
    }

    private function violation(string $path, string $message): ConstraintViolation
    {
        return new ConstraintViolation(
            $message,
            null,
            [],
            null,
            $path,
            null
        );
    }
}
