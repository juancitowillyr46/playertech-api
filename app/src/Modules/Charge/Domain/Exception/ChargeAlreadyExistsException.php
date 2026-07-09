<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Exception;
use App\Shared\Domain\Exception\ConflictException;
final class ChargeAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('The charge already exists.');
    }
}
