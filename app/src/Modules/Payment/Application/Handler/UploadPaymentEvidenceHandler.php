<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Application\Command\UploadPaymentEvidenceCommand;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Modules\Payment\Domain\PaymentEvidence\PaymentEvidence;
use App\Modules\Payment\Domain\PaymentEvidence\PaymentEvidenceId;
use App\Modules\Payment\Infrastructure\Persistence\PaymentEvidenceRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
final readonly class UploadPaymentEvidenceHandler
{
    public function __construct(private PaymentRepository $paymentRepository, private PaymentEvidenceRepository $paymentEvidenceRepository) {}
    public function __invoke(UploadPaymentEvidenceCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);
        $paymentId = new PaymentId($command->paymentId);
        if (null === $this->paymentRepository->findById($academyId, $paymentId)) { throw new \RuntimeException('Payment not found.'); }
        $evidence = PaymentEvidence::create(PaymentEvidenceId::generate(), $academyId, $paymentId, $command->fileName, $command->filePath, $command->mimeType, AuditTrail::create($command->actorId));
        $this->paymentEvidenceRepository->save($evidence);
    }
}
