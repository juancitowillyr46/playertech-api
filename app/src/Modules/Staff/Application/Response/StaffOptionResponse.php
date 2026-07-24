<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Response;

final readonly class StaffOptionResponse
{
    private function __construct(
        private string $id,
        private string $label,
    ) {
    }

    /**
     * @param array{id:string,label:string} $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            $row['id'],
            $row['label'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
        ];
    }
}
