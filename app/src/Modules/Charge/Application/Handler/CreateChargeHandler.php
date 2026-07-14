<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Application\Command\CreateChargeCommand;
use App\Modules\Charge\Application\Response\ChargeResponse;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
final readonly class CreateChargeHandler
{
    public function __construct(private MembershipRepository $membershipRepository, private PaymentConceptRepository $paymentConceptRepository, private ChargeRepository $chargeRepository) {}
    public function __invoke(CreateChargeCommand $command): ChargeResponse
    {
        $academyId = new AcademyId($command->academyId);
        $playerId = new PlayerId($command->playerId);
        $membership = $this->membershipRepository->findActiveByPlayerId($academyId, $playerId);
        if (null === $membership) { throw new \App\Modules\Charge\Domain\Exception\ChargeNotFoundException(); }
        if (null === $this->paymentConceptRepository->findById($academyId, new \App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId($command->paymentConceptId))) { throw new \App\Modules\Charge\Domain\Exception\ChargeNotFoundException(); }
        $charge = Charge::create(ChargeId::generate(), $academyId, $playerId, $membership->id(), new \App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId($command->paymentConceptId), $command->description, (float) $command->amount, new \DateTimeImmutable($command->dueDate), $command->source, AuditTrail::create($command->actorId));
        $this->chargeRepository->save($charge);
        return ChargeResponse::fromCharge($charge);
    }
}
