<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\TeamStaffAssignment;
final readonly class StaffRole
{
    public const HEAD_COACH='HEAD_COACH'; public const ASSISTANT_COACH='ASSISTANT_COACH'; public const GOALKEEPER_COACH='GOALKEEPER_COACH'; public const PHYSICAL_PREPARER='PHYSICAL_PREPARER'; public const NUTRITIONIST='NUTRITIONIST'; public const PHYSIOTHERAPY='PHYSIOTHERAPY';
    public function __construct(private string $value){ if(!in_array($value,[self::HEAD_COACH,self::ASSISTANT_COACH,self::GOALKEEPER_COACH,self::PHYSICAL_PREPARER,self::NUTRITIONIST,self::PHYSIOTHERAPY],true)){ throw new \InvalidArgumentException(sprintf('Invalid staff role: %s',$value)); } }
    public function value(): string { return $this->value; } public function __toString(): string { return $this->value; }
}
