<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Import;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Player\Domain\Import\PlayerImportJobId;
use App\Modules\Player\Domain\Import\PlayerImportJobRepository;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ProcessPlayerImportJobHandler
{
    public function __construct(
        private PlayerImportJobRepository $jobRepository,
        private PlayerRepository $playerRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(ProcessPlayerImportJobMessage $message): void
    {
        $academyId = new AcademyId($message->academyId);
        $job = $this->jobRepository->findById($academyId, new PlayerImportJobId($message->jobId));

        if (null === $job) {
            return;
        }

        $job->start();
        $this->jobRepository->save($job);

        try {
            $spreadsheet = IOFactory::load($job->filePath());
            $worksheet = $spreadsheet->getSheetByName('Datos') ?? $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);
            $header = array_map('strtolower', array_map('trim', array_values(array_shift($rows))));

            $expected = ['documenttype', 'firstname', 'lastname', 'birthdate', 'documentnumber', 'email', 'phone', 'nationality', 'gender', 'federationid', 'dominantfoot'];
            if ($header !== $expected) {
                throw new \RuntimeException('La plantilla del Excel no coincide con el formato esperado.');
            }

            $rows = array_values(array_filter($rows, static fn (array $row): bool => [] !== array_filter($row, static fn ($value): bool => null !== $value && '' !== trim((string) $value))));
            $job->setTotals(count($rows), 0, 0, 0);
            $job->markProcessing();
            $this->jobRepository->save($job);

            $errors = [];
            $success = 0;
            $processed = 0;
            $category = $this->categoryRepository->findById($academyId, new CategoryId($message->categoryId));
            if (null === $category) {
                throw new \RuntimeException('La categoría seleccionada no existe.');
            }

            if ($job->categoryId() !== $message->categoryId) {
                throw new \RuntimeException('La categoría del job no coincide con la categoría solicitada.');
            }

            foreach ($rows as $index => $row) {
                $processed++;
                $line = $index + 2;
                $documentNumber = trim((string) ($row['E'] ?? ''));

                try {
                    $birthDate = \DateTimeImmutable::createFromFormat('Y-m-d', trim((string) ($row['D'] ?? '')));
                    if (false === $birthDate) {
                        throw new \RuntimeException('birthDate inválida.');
                    }

                    $documentType = trim((string) ($row['A'] ?? ''));
                    $firstName = trim((string) ($row['B'] ?? ''));
                    $lastName = trim((string) ($row['C'] ?? ''));
                    $email = trim((string) ($row['F'] ?? ''));
                    $phone = trim((string) ($row['G'] ?? ''));
                    $nationality = trim((string) ($row['H'] ?? ''));
                    $gender = trim((string) ($row['I'] ?? ''));
                    $federationId = trim((string) ($row['J'] ?? ''));
                    $dominantFoot = trim((string) ($row['K'] ?? ''));

                    if ('' === $documentType) {
                        throw new \RuntimeException('documentType es obligatorio.');
                    }
                    if ('' === $firstName) {
                        throw new \RuntimeException('firstName es obligatorio.');
                    }
                    if ('' === $lastName) {
                        throw new \RuntimeException('lastName es obligatorio.');
                    }
                    if ('' === $documentNumber) {
                        throw new \RuntimeException('documentNumber es obligatorio.');
                    }
                    if (null !== $this->playerRepository->findOneByDocumentNumber($academyId, $documentNumber)) {
                        throw new \RuntimeException('Documento duplicado.');
                    }

                    $player = Player::create(
                        PlayerId::generate(),
                        $academyId,
                        $documentType,
                        $firstName,
                        $lastName,
                        $birthDate,
                        $documentNumber,
                        $email ?: null,
                        $phone ?: null,
                        $nationality ?: null,
                        $gender ?: null,
                        $federationId ?: null,
                        $dominantFoot ?: null,
                        $category->id(),
                        null,
                        AuditTrail::create($message->actorId),
                    );

                    $this->playerRepository->save($player);
                    $success++;
                } catch (\Throwable $e) {
                    $errors[] = [
                        'row' => $line,
                        'field' => $this->fieldFromMessage($e->getMessage()),
                        'message' => $e->getMessage(),
                    ];
                }

                $job->setProgress((int) round(($processed / max(1, count($rows))) * 100));
                $job->setTotals(count($rows), $processed, $success, count($errors));
                $job->setErrors($errors);
                $this->jobRepository->save($job);
            }

            if ([] === $errors) {
                $job->markCompleted();
            } else {
                $job->markCompletedWithErrors();
            }

            $job->setTotals(count($rows), $processed, $success, count($errors));
            $job->setErrors($errors);
            $this->jobRepository->save($job);
        } catch (\Throwable $e) {
            $job->markFailed($e->getMessage());
            $this->jobRepository->save($job);
        }
    }

    private function fieldFromMessage(string $message): string
    {
        return match (true) {
            str_contains($message, 'documentType') => 'documentType',
            str_contains($message, 'firstName') => 'firstName',
            str_contains($message, 'lastName') => 'lastName',
            str_contains($message, 'birthDate') => 'birthDate',
            str_contains($message, 'documentNumber') => 'documentNumber',
            str_contains($message, 'category') => 'categoryId',
            default => 'file',
        };
    }
}
