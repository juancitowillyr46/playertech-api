<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Command;
final readonly class RegisterStaffMemberCommand { public function __construct(public string $actorId, public string $academyId, public string $userId) {}}
