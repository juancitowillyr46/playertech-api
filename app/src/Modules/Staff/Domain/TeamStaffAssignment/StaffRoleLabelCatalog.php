<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\TeamStaffAssignment;
final class StaffRoleLabelCatalog
{
    public static function label(string $role): string
    {
        return match ($role) {
            StaffRole::HEAD_COACH => 'Entrenador principal',
            StaffRole::ASSISTANT_COACH => 'Entrenador asistente',
            StaffRole::GOALKEEPER_COACH => 'Entrenador de porteros',
            StaffRole::PHYSICAL_PREPARER => 'Preparador físico',
            StaffRole::NUTRITIONIST => 'Nutricionista',
            StaffRole::PHYSIOTHERAPY => 'Fisioterapia',
            default => 'Rol técnico',
        };
    }
}
