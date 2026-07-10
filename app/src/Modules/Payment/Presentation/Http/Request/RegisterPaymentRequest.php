<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class RegisterPaymentRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "membershipId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "membershipId" debe ser un UUID válido.')]
        public ?string $membershipId = null,
        #[Assert\NotBlank(message: 'El campo "playerId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "playerId" debe ser un UUID válido.')]
        public ?string $playerId = null,
        #[Assert\NotBlank(message: 'El campo "guardianId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "guardianId" debe ser un UUID válido.')]
        public ?string $guardianId = null,
        #[Assert\NotBlank(message: 'El campo "paymentConceptId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "paymentConceptId" debe ser un UUID válido.')]
        public ?string $paymentConceptId = null,
        #[Assert\NotBlank(message: 'El campo "paymentDate" es obligatorio.')]
        public ?string $paymentDate = null,
        #[Assert\NotBlank(message: 'El campo "amount" es obligatorio.')]
        public ?string $amount = null,
        public ?string $notes = null,
    ) {}
}
