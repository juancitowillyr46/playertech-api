<?php
declare(strict_types=1);
namespace App\Modules\Staff\Infrastructure\Persistence\Doctrine\Type;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
final class StaffIdType extends AbstractUuidType { public const NAME='staff_id'; protected function getValueObjectClass(): string { return StaffId::class; } public function getName(): string { return self::NAME; } }
