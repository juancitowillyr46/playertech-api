<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Payment\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\Player\Domain\Player\Player;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Tests\Support\Database\SchemaResetter;

final class PaymentQueryControllerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private string $guardianId;
    private string $paymentConceptId;
    private string $academyId;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $doctrine = $container->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        SchemaResetter::reset($this->entityManager, [
            $this->entityManager->getClassMetadata(Academy::class),
            $this->entityManager->getClassMetadata(AccountUser::class),
            $this->entityManager->getClassMetadata(Player::class),
            $this->entityManager->getClassMetadata(LegalGuardian::class),
            $this->entityManager->getClassMetadata(Membership::class),
            $this->entityManager->getClassMetadata(PaymentConcept::class),
            $this->entityManager->getClassMetadata(Payment::class),
        ]);

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email('academy@test.local'),
            new PhoneNumber('+51 999 999 999'),
            'Colombia',
            'Cundinamarca',
            'signup',
            new Address('Av. Principal 123'),
            new City('Lima'),
            null,
            AuditTrail::create('system'),
        );

        $player = Player::create(
            \App\Modules\Player\Domain\Player\PlayerId::generate(),
            $academy->id(),
            'DNI',
            'Juan',
            'Perez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('system'),
        );

        $guardian = LegalGuardian::create(
            LegalGuardianId::generate(),
            $academy->id(),
            'Maria',
            'Lopez',
            '+51 999 111 222',
            'maria@example.com',
            'Madre',
            AuditTrail::create('system'),
        );

        $membership = Membership::create(
            \App\Modules\Membership\Domain\Membership\MembershipId::generate(),
            $academy->id(),
            $player->id(),
            $guardian->id(),
            AuditTrail::create('system'),
        );

        $paymentConcept = PaymentConcept::create(
            \App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId::generate(),
            $academy->id(),
            'MATRICULA',
            'Matrícula',
            'Cobro inicial',
            AuditTrail::create('system'),
        );

        $payment = Payment::create(
            PaymentId::generate(),
            $academy->id(),
            $membership->id(),
            $player->id(),
            $guardian->id(),
            $paymentConcept->id(),
            new \DateTimeImmutable('2026-07-14'),
            150000.00,
            'CASH',
            'Pago inicial',
            AuditTrail::create('system'),
        );

        $user = new AccountUser();
        $user->setEmail('coach@test.local');
        $user->setPasswordHash('hashed-password');
        $user->setAcademyId($academy->id()->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setFullName('Coach Test');

        foreach ([$academy, $player, $guardian, $membership, $paymentConcept, $payment, $user] as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();

        $this->academyId = $academy->id()->value();
        $this->guardianId = $guardian->id()->value();
        $this->paymentConceptId = $paymentConcept->id()->value();
    }

    public function testItReturnsGuardianPaymentHistory(): void
    {
        $tokenStorage = self::$kernel->getContainer()->get('security.token_storage');
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(null);
        $token->method('hasAttribute')->with('tenant_context')->willReturn(true);
        $token->method('getAttribute')->with('tenant_context')->willReturn([
            'mode' => 'tenant',
            'academy_id' => $this->academyId,
            'user_id' => 'user-id',
            'role' => AccountUser::ROLE_ACADEMY_ADMIN,
            'roles' => [AccountUser::ROLE_ACADEMY_ADMIN],
        ]);
        $tokenStorage->setToken($token);

        /** @var \App\Modules\Payment\Presentation\Http\Academy\PaymentQueryController $controller */
        $controller = self::$kernel->getContainer()->get(\App\Modules\Payment\Presentation\Http\Academy\PaymentQueryController::class);
        $historyResponse = $controller->guardianHistory($this->guardianId);

        self::assertSame(200, $historyResponse->getStatusCode());
        $historyPayload = json_decode($historyResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertCount(1, $historyPayload['data']);
        self::assertSame($this->paymentConceptId, $historyPayload['data'][0]['paymentConceptId']);
        self::assertSame('CASH', $historyPayload['data'][0]['method']);
    }
}
