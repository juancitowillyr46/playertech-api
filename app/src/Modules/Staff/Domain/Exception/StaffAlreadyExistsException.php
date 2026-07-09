<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\Exception;
use App\Shared\Domain\Exception\ConflictException;
final class StaffAlreadyExistsException extends ConflictException { public function __construct(){ parent::__construct('Staff member already exists.'); } }
