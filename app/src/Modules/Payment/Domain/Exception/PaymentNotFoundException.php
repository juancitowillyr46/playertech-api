<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\Exception;
use App\Shared\Domain\Exception\NotFoundException;
final class PaymentNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('The payment was not found.');
    }
}
