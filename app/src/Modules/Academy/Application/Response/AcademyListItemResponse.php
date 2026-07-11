<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Modules\Academy\Domain\Academy\Academy;

final readonly class AcademyListItemResponse
{
    private function __construct(
        private string $id,
        private string $name,
        private string $contactEmail,
        private string $registrationSource,
        private string $status,
    ) {
    }

    public static function fromAcademy(Academy $academy): self
    {
        return new self(
            $academy->id()->value(),
            $academy->name()->value(),
            $academy->contactEmail()->value(),
            $academy->registrationSource(),
            $academy->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contactEmail' => $this->contactEmail,
            'registrationSource' => $this->registrationSource,
            'status' => $this->status,
        ];
    }
}
