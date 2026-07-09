<?php
declare(strict_types=1);
namespace App\Modules\Charge\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class CreateChargeRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "membership_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "membership_id" debe ser un UUID válido.')]
        public ?string $membershipId = null,
        #[Assert\NotBlank(message: 'El campo "payment_concept_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "payment_concept_id" debe ser un UUID válido.')]
        public ?string $paymentConceptId = null,
        #[Assert\NotBlank(message: 'El campo "description" es obligatorio.')]
        public ?string $description = null,
        #[Assert\NotBlank(message: 'El campo "amount" es obligatorio.')]
        public ?string $amount = null,
    ) {}
}
