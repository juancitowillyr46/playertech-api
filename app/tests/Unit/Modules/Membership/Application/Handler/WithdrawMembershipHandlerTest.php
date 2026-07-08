<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Application\Command\CreateMembershipCommand;
use App\Modules\Membership\Application\Command\WithdrawMembershipCommand;
use App\Modules\Membership\Application\Handler\CreateMembershipHandler;
use App\Modules\Membership\Application\Handler\WithdrawMembershipHandler;
use App\Modules\Membership\Application\Handler\ShowActiveMembershipHandler;
use App\Modules\Membership\Application\Query\ShowActiveMembershipQuery;
use App\Modules\Membership\Domain\Exception\MembershipNotFoundException;
use App\Modules\Player\Domain\Player\PlayerId;
use PHPUnit\Framework\TestCase;

final class WithdrawMembershipHandlerTest extends TestCase
{
    public function testItWithdrawsActiveMembership(): void
    {
        $repository = new InMemoryMembershipRepository();
        $creator = new CreateMembershipHandler($repository);
        $withdrawer = new WithdrawMembershipHandler($repository);
        $reader = new ShowActiveMembershipHandler($repository);

        $creator(new CreateMembershipCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            '019eec93-9a11-7432-bd04-52306b2b3d90',
            '019eec93-9a11-7432-bd04-52306b2b3d91',
        ));

        $withdrawer(new WithdrawMembershipCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            '019eec93-9a11-7432-bd04-52306b2b3d90',
        ));

        $this->expectException(MembershipNotFoundException::class);

        $reader(new ShowActiveMembershipQuery(
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
        ));
    }
}
