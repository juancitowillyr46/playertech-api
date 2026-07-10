<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Application\Command\CreateMembershipCommand;
use App\Modules\Membership\Application\Handler\CreateMembershipHandler;
use App\Modules\Membership\Application\Handler\ShowActiveMembershipHandler;
use App\Modules\Membership\Application\Query\ShowActiveMembershipQuery;
use App\Modules\Membership\Application\Services\MembershipFinder;
use App\Modules\Membership\Domain\Exception\MembershipNotFoundException;
use App\Modules\Player\Domain\Player\PlayerId;
use PHPUnit\Framework\TestCase;

final class ShowActiveMembershipHandlerTest extends TestCase
{
    public function testItShowsActiveMembershipForPlayer(): void
    {
        $repository = new InMemoryMembershipRepository();
        $creator = new CreateMembershipHandler($repository);
        $reader = new ShowActiveMembershipHandler(new MembershipFinder($repository));

        $creator(new CreateMembershipCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            '019eec93-9a11-7432-bd04-52306b2b3d90',
            '019eec93-9a11-7432-bd04-52306b2b3d91',
        ));

        $response = $reader(new ShowActiveMembershipQuery(
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
        ));

        self::assertSame('019eec93-9a11-7432-bd04-52306b2b3d90', $response->toArray()['playerId']);
    }

    public function testItThrowsWhenMembershipDoesNotExist(): void
    {
        $reader = new ShowActiveMembershipHandler(new MembershipFinder(new InMemoryMembershipRepository()));

        $this->expectException(MembershipNotFoundException::class);

        $reader(new ShowActiveMembershipQuery(
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
        ));
    }
}
