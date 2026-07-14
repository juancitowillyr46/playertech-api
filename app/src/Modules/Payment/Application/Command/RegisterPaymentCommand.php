<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Command;
final readonly class RegisterPaymentCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $membershipId,
        public string $playerId,
        public string $guardianId,
        public string $paymentConceptId,
        public string $paymentDate,
        public string $amount,
        public string $method,
        /** @var array<int, array{chargeId:string, amount:string}> */
        public array $allocations = [],
        public ?string $notes = null
    ) {}
}
