<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class UploadPaymentEvidenceRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "paymentId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "paymentId" debe ser un UUID válido.')]
        public ?string $paymentId = null,
        #[Assert\NotBlank(message: 'El campo "fileName" es obligatorio.')]
        public ?string $fileName = null,
        #[Assert\NotBlank(message: 'El campo "filePath" es obligatorio.')]
        public ?string $filePath = null,
        #[Assert\NotBlank(message: 'El campo "mimeType" es obligatorio.')]
        public ?string $mimeType = null,
    ) {}
}
