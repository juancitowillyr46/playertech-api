<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Command;
final readonly class CreateChargeCommand
{
    public function __construct(public string $actorId, public string $academyId, public string $membershipId, public string $paymentConceptId, public string $description, public string $amount) {}
}
