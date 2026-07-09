<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\Exception;
use App\Shared\Domain\Exception\ConflictException;
final class PaymentAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('The payment already exists.');
    }
}
