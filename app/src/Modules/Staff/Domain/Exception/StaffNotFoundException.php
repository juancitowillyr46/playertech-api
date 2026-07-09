<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\Exception;
use App\Shared\Domain\Exception\NotFoundException;
final class StaffNotFoundException extends NotFoundException { public function __construct(){ parent::__construct('Staff member not found.'); } }
