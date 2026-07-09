<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Exception;
use App\Shared\Domain\Exception\NotFoundException;
final class ChargeNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('The charge was not found.');
    }
}
