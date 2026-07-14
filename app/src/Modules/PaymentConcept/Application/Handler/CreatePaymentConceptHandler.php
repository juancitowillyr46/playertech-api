<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Command\CreatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Response\PaymentConceptResponse;
use App\Modules\PaymentConcept\Application\Services\PaymentConceptCodeGenerator;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
final readonly class CreatePaymentConceptHandler
{
    public function __construct(
        private PaymentConceptRepository $repository,
        private PaymentConceptCodeGenerator $codeGenerator,
    ) {}
    public function __invoke(CreatePaymentConceptCommand $command): PaymentConceptResponse
    {
        $academyId = new AcademyId($command->academyId);
        $name = $command->input->name ?? '';
        $code = $this->codeGenerator->generate($academyId, $name);
        $paymentConcept = PaymentConcept::create(PaymentConceptId::generate(), $academyId, $code, $name, $command->input->description, AuditTrail::create($command->actorId));
        $this->repository->save($paymentConcept);
        return PaymentConceptResponse::fromPaymentConcept($paymentConcept);
    }
}
