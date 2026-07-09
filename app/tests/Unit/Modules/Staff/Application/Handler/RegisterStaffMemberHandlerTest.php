<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Staff\Application\Command\RegisterStaffMemberCommand;
use App\Modules\Staff\Application\Handler\RegisterStaffMemberHandler;
use App\Modules\Staff\Domain\Exception\StaffAlreadyExistsException;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
final class RegisterStaffMemberHandlerTest extends TestCase
{
    public function testItRegistersStaffMemberFromExistingAcademyUser(): void
    {
        $staffRepository = new InMemoryStaffRepository();
        $user = new AccountUser();
        $user->setId('019eec93-9a11-7432-bd04-52306b2b3d8e');
        $user->setAcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('find')->with('019eec93-9a11-7432-bd04-52306b2b3d8e')->willReturn($user);
        $entityManager->method('getRepository')->willReturn($repo);
        $handler = new RegisterStaffMemberHandler($staffRepository, $entityManager);
        $response = $handler(new RegisterStaffMemberCommand('019eec93-9a11-7432-bd04-52306b2b3d00', '019eec93-9a11-7432-bd04-52306b2b3d8f', '019eec93-9a11-7432-bd04-52306b2b3d8e'));
        self::assertSame('019eec93-9a11-7432-bd04-52306b2b3d8e', $response->toArray()['user_id']);
        self::assertCount(1, $staffRepository->items);
    }
    public function testItRejectsDuplicateStaffMember(): void
    {
        $staffRepository = new InMemoryStaffRepository();
        $user = new AccountUser();
        $user->setId('019eec93-9a11-7432-bd04-52306b2b3d8e');
        $user->setAcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('find')->willReturn($user);
        $entityManager->method('getRepository')->willReturn($repo);
        $handler = new RegisterStaffMemberHandler($staffRepository, $entityManager);
        $command = new RegisterStaffMemberCommand('019eec93-9a11-7432-bd04-52306b2b3d00', '019eec93-9a11-7432-bd04-52306b2b3d8f', '019eec93-9a11-7432-bd04-52306b2b3d8e');
        $handler($command);
        $this->expectException(StaffAlreadyExistsException::class);
        $handler($command);
    }
}
