<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Service;

use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\Document\DocumentType;
use App\Shared\Domain\Nationality\Nationality;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

final readonly class PlayerImportTemplateFactory
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function create(AcademyId $academyId): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $referenceSheet = $spreadsheet->getActiveSheet();
        $referenceSheet->setTitle('Referencias');

        $referenceSheet->fromArray([
            ['Instrucciones:'],
            ['1. Ubica la columna que deseas utilizar en la pestaña "Datos" del Excel. Ejemplo: documentType'],
            ['2. Busca en la pestaña "Referencias" la tabla que se relaciona con el nombre de la columna'],
            ['3. Ubicate en la columna Código y copia el texto que contiene la celda. Ejemplo: CC'],
            ['4. Vuelve a la pestaña "Datos" y pega el valor en la celda que se encuentra encima de la columna. Ejemplo: documentType'],
        ], null, 'A1');
        $referenceSheet->getStyle('A1')->getFont()->setBold(true);
        $referenceSheet->getStyle('A2:A5')->getAlignment()->setWrapText(true);

        $currentRow = 8;
        $categories = $this->categoryRepository->findActiveByAcademy($academyId);
        $currentRow = $this->writeTable($referenceSheet, $currentRow, 'Categorías disponibles (categories)', array_map(
            static fn (Category $category): array => [
                'name' => $category->name()->value(),
                'code' => $category->categoryKey(),
            ],
            $categories
        ));

        $currentRow = $this->writeFormatTable($referenceSheet, $currentRow + 2, [
            ['field' => 'birthDate', 'format' => 'YYYY-MM-DD', 'example' => '1989-09-04'],
            ['field' => 'email', 'format' => 'correo válido', 'example' => 'juan.rodas.manez@gmail.com'],
            ['field' => 'phone', 'format' => 'número colombiano; se guarda con prefijo +57', 'example' => '3125953354'],
        ]);

        $currentRow = $this->writeTable(
            $referenceSheet,
            $currentRow + 2,
            'Tipo de documento (documentType)',
            array_map(
                static fn (array $option): array => [
                    'name' => $option['label'],
                    'code' => $option['value'],
                ],
                DocumentType::options(),
            ),
        );

        $currentRow = $this->writeTable(
            $referenceSheet,
            $currentRow + 2,
            'Nacionalidades (nationality)',
            array_map(
                static fn (Nationality $nationality): array => [
                    'name' => $nationality->label(),
                    'code' => $nationality->value,
                ],
                Nationality::cases(),
            ),
        );

        $currentRow = $this->writeTable($referenceSheet, $currentRow + 2, 'Pies dominantes (dominantFoot)', [
            ['name' => 'Derecho', 'code' => 'RIGHT'],
            ['name' => 'Izquierdo', 'code' => 'LEFT'],
            ['name' => 'Ambidiestro', 'code' => 'BOTH'],
        ]);

        $this->writeTable($referenceSheet, $currentRow + 2, 'Genero (gender)', [
            ['name' => 'Masculino', 'code' => 'MALE'],
            ['name' => 'Femenino', 'code' => 'FEMALE'],
        ]);

        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Datos');

        $headers = ['documentType', 'firstName', 'lastName', 'birthDate', 'documentNumber', 'email', 'phone', 'nationality', 'gender', 'federationId', 'dominantFoot'];
        $dataSheet->fromArray([$headers], null, 'A1');
        $dataSheet->freezePane('A2');

        foreach ([$referenceSheet, $dataSheet] as $sheet) {
            foreach (range('A', $sheet->getHighestColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        return $spreadsheet;
    }

    private function writeTable(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $startRow, string $title, array $rows): int
    {
        $sheet->setCellValue('A' . $startRow, $title);
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);
        $headers = ['Nombre', 'Código'];
        $sheet->fromArray([$headers], null, 'A' . ($startRow + 1));
        $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle('A' . ($startRow + 1) . ':' . $endColumn . ($startRow + 1))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9EAF7');

        $rowNumber = $startRow + 2;
        foreach ($rows as $row) {
            $values = [$row['name'] ?? $row['label'] ?? '', $row['code'] ?? $row['value'] ?? ''];
            $sheet->fromArray([$values], null, 'A' . $rowNumber);
            $rowNumber++;
        }

        return $rowNumber - 1;
    }

    private function writeFormatTable(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $startRow, array $rows): int
    {
        $sheet->setCellValue('A' . $startRow, 'Formas correctas');
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);
        $sheet->fromArray([['Campos', 'Formatos', 'Ejemplos']], null, 'A' . ($startRow + 1));
        $sheet->getStyle('A' . ($startRow + 1) . ':C' . ($startRow + 1))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9EAF7');

        $rowNumber = $startRow + 2;
        foreach ($rows as $row) {
            $sheet->fromArray([[$row['field'], $row['format'], $row['example']]], null, 'A' . $rowNumber);
            $rowNumber++;
        }

        return $rowNumber - 1;
    }
}
