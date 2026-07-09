<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class RegisterPaymentRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "membership_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "membership_id" debe ser un UUID válido.')]
        public ?string $membershipId = null,
        #[Assert\NotBlank(message: 'El campo "player_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "player_id" debe ser un UUID válido.')]
        public ?string $playerId = null,
        #[Assert\NotBlank(message: 'El campo "guardian_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "guardian_id" debe ser un UUID válido.')]
        public ?string $guardianId = null,
        #[Assert\NotBlank(message: 'El campo "payment_concept_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "payment_concept_id" debe ser un UUID válido.')]
        public ?string $paymentConceptId = null,
        #[Assert\NotBlank(message: 'El campo "payment_date" es obligatorio.')]
        public ?string $paymentDate = null,
        #[Assert\NotBlank(message: 'El campo "amount" es obligatorio.')]
        public ?string $amount = null,
        public ?string $notes = null,
    ) {}
}
