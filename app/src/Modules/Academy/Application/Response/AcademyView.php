<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Modules\Academy\Domain\Academy\Academy;

final readonly class AcademyView
{
    private function __construct(
        private array $data,
    ) {
    }

    public static function fromAcademy(Academy $academy): self
    {
        return new self([
            'id' => $academy->id()->value(),
            'name' => $academy->name()->value(),
            'contact_email' => $academy->contactEmail()->value(),
            'phone' => $academy->phone()?->value(),
            'address' => $academy->address()?->value(),
            'city' => $academy->city()?->value(),
            'logo' => $academy->logo()?->value(),
            'status' => $academy->status()->value(),
            'created_at' => $academy->auditTrail()->createdAt()->value()->format(DATE_ATOM),
            'created_by' => $academy->auditTrail()->createdBy(),
            'updated_at' => $academy->auditTrail()->updatedAt()?->value()?->format(DATE_ATOM),
            'updated_by' => $academy->auditTrail()->updatedBy(),
        ]);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
