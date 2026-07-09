<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class UploadPaymentEvidenceRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "payment_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "payment_id" debe ser un UUID válido.')]
        public ?string $paymentId = null,
        #[Assert\NotBlank(message: 'El campo "file_name" es obligatorio.')]
        public ?string $fileName = null,
        #[Assert\NotBlank(message: 'El campo "file_path" es obligatorio.')]
        public ?string $filePath = null,
        #[Assert\NotBlank(message: 'El campo "mime_type" es obligatorio.')]
        public ?string $mimeType = null,
    ) {}
}
