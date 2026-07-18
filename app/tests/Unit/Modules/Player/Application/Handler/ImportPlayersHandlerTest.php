<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Player\Application\Command\ImportPlayersCommand;
use App\Modules\Player\Application\Handler\ImportPlayersHandler;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImportPlayersHandlerTest extends TestCase
{
    public function testItImportsPlayersAndAssignsCategories(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d70');
        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB_14',
            new Name('Sub 14'),
            new MinimumAge(12),
            new MaximumAge(14),
            new Description('Categoria base'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ImportPlayersHandler($playerRepository, $categoryRepository);
        $file = $this->createWorkbook([
            ['document_type', 'first_name', 'last_name', 'birth_date', 'document_number', 'nationality', 'gender', 'federation_id', 'dominant_foot', 'category_key'],
            ['DNI', 'Juan', 'Pérez', '2014-05-18', '12345678', 'Colombiana', 'Masculino', 'F001', 'Derecho', 'SUB_14'],
        ]);

        $responses = $handler(new ImportPlayersCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $file,
        ));

        self::assertCount(1, $responses);
        self::assertSame($categoryId->value(), $responses[0]->toArray()['categoryId']);
        self::assertCount(1, $playerRepository->players);
    }

    public function testItRejectsInvalidCategoryDuringImport(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository();

        $handler = new ImportPlayersHandler($playerRepository, $categoryRepository);
        $file = $this->createWorkbook([
            ['document_type', 'first_name', 'last_name', 'birth_date', 'document_number', 'nationality', 'gender', 'federation_id', 'dominant_foot', 'category_key'],
            ['DNI', 'Juan', 'Pérez', '2014-05-18', '12345678', '', '', '', '', 'SUB_14'],
        ]);

        $this->expectException(ValidationException::class);

        $handler(new ImportPlayersCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $file,
        ));
    }

    /**
     * @param array<int, array<int, string>> $rows
     */
    private function createWorkbook(array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($rows as $rowIndex => $row) {
            foreach (array_values($row) as $columnIndex => $value) {
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($columnIndex + 1).($rowIndex + 1),
                    $value
                );
            }
        }

        $path = tempnam(sys_get_temp_dir(), 'player-import-');
        if (false === $path) {
            self::fail('No se pudo crear un archivo temporal para la importación.');
        }

        $xlsxPath = $path.'.xlsx';
        rename($path, $xlsxPath);

        $writer = new Xlsx($spreadsheet);
        $writer->save($xlsxPath);

        return new UploadedFile(
            $xlsxPath,
            'players.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
    }
}
