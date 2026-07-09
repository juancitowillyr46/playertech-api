<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Charge\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Application\Handler\ListPendingChargesHandler;
use App\Modules\Charge\Application\Query\ListPendingChargesQuery;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class ListPendingChargesHandlerTest extends TestCase
{
    public function testItListsPendingCharges(): void
    {
        $academyId = AcademyId::generate();
        $repo = new InMemoryChargeRepository();
        $repo->save(Charge::create(ChargeId::generate(), $academyId, MembershipId::generate(), PaymentConceptId::generate(), 'Uniforme', 80.00, AuditTrail::create('actor-id')));
        $handler = new ListPendingChargesHandler($repo);
        $items = $handler(new ListPendingChargesQuery($academyId->value()));
        self::assertCount(1, $items);
    }
}
