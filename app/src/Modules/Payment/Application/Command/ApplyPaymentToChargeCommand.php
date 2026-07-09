<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Command;
final readonly class ApplyPaymentToChargeCommand
{
    public function __construct(public string $actorId, public string $academyId, public string $paymentId, public string $chargeId, public string $amount) {}
}
