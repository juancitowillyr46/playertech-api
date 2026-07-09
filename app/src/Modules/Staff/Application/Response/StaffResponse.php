<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Response;
use App\Modules\Staff\Domain\Staff\Staff;
final readonly class StaffResponse { public function __construct(public string $id, public string $academyId, public string $userId, public string $status) {} public static function fromStaff(Staff $staff): self { return new self($staff->id()->value(), $staff->academyId()->value(), $staff->userId(), $staff->status()->value()); } public function toArray(): array { return ['id'=>$this->id,'academy_id'=>$this->academyId,'user_id'=>$this->userId,'status'=>$this->status]; } }
