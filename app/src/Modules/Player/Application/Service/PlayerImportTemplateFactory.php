<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Service;

use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Academy\Domain\Academy\AcademyId;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

final readonly class PlayerImportTemplateFactory
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function create(AcademyId $academyId, ?CategoryId $selectedCategoryId = null): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $dataSheet = $spreadsheet->getActiveSheet();
        $dataSheet->setTitle('Datos');

        $headers = ['documentType', 'firstName', 'lastName', 'birthDate', 'documentNumber', 'email', 'phone', 'nationality', 'gender', 'federationId', 'dominantFoot'];
        $dataSheet->fromArray([$headers], null, 'A1');
        $dataSheet->freezePane('A2');

        $referenceSheet = $spreadsheet->createSheet();
        $referenceSheet->setTitle('Referencias');

        if (null !== $selectedCategoryId) {
            $selectedCategory = $this->categoryRepository->findById($academyId, $selectedCategoryId);
            if (null !== $selectedCategory) {
                $referenceSheet->fromArray([['Categoría seleccionada', 'label', 'value', 'categoryKey', 'status']], null, 'A1');
                $referenceSheet->fromArray([[
                    'Categoría seleccionada',
                    $selectedCategory->name()->value(),
                    $selectedCategory->id()->value(),
                    $selectedCategory->categoryKey(),
                    $selectedCategory->status()->value(),
                ]], null, 'A2');
            }
        }

        $this->writeSection($referenceSheet, 8, 'Tipos de documento', [
            ['label' => 'Cédula de ciudadanía', 'value' => 'CC'],
            ['label' => 'Cédula de extranjería', 'value' => 'CE'],
            ['label' => 'Tarjeta de identidad', 'value' => 'TI'],
            ['label' => 'PPT', 'value' => 'PPT'],
            ['label' => 'Pasaporte', 'value' => 'PASSPORT'],
        ]);

        $this->writeSection($referenceSheet, 16, 'Nacionalidades', [
            ['label' => 'Colombia', 'value' => 'Colombia'],
            ['label' => 'Perú', 'value' => 'Perú'],
            ['label' => 'Chile', 'value' => 'Chile'],
            ['label' => 'Ecuador', 'value' => 'Ecuador'],
            ['label' => 'México', 'value' => 'México'],
            ['label' => 'España', 'value' => 'España'],
        ]);

        $this->writeSection($referenceSheet, 24, 'Pies dominantes', [
            ['label' => 'Derecho', 'value' => 'RIGHT'],
            ['label' => 'Izquierdo', 'value' => 'LEFT'],
            ['label' => 'Ambidiestro', 'value' => 'BOTH'],
        ]);

        $categories = $this->categoryRepository->findActiveByAcademy($academyId);

        $referenceSheet->fromArray([['Categorías activas', 'label', 'value', 'categoryKey', 'status']], null, 'A30');
        $row = 31;
        foreach ($categories as $category) {
            $referenceSheet->fromArray([[
                'Categoría',
                $category->name()->value(),
                $category->id()->value(),
                $category->categoryKey(),
                $category->status()->value(),
            ]], null, 'A' . $row);
            $row++;
        }

        foreach ([$dataSheet, $referenceSheet] as $sheet) {
            foreach (range('A', $sheet->getHighestColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        return $spreadsheet;
    }

    private function writeSection(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $startRow, string $title, array $rows): void
    {
        $sheet->setCellValue('A' . $startRow, $title);
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);
        $sheet->fromArray([['label', 'value']], null, 'A' . ($startRow + 1));
        $sheet->getStyle('A' . ($startRow + 1) . ':B' . ($startRow + 1))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9EAF7');

        $rowNumber = $startRow + 2;
        foreach ($rows as $row) {
            $sheet->fromArray([[$row['label'], $row['value']]], null, 'A' . $rowNumber);
            $rowNumber++;
        }
    }
}
