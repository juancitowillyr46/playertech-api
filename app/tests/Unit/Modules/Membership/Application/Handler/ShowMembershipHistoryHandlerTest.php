<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Membership\Application\Handler;

use App\Modules\Membership\Application\Command\CreateMembershipCommand;
use App\Modules\Membership\Application\Handler\CreateMembershipHandler;
use App\Modules\Membership\Application\Handler\ShowMembershipHistoryHandler;
use App\Modules\Membership\Application\Query\ShowMembershipHistoryQuery;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use PHPUnit\Framework\TestCase;

final class ShowMembershipHistoryHandlerTest extends TestCase
{
    public function testItReturnsMembershipHistoryForPlayer(): void
    {
        $repository = new InMemoryMembershipRepository();
        $creator = new CreateMembershipHandler($repository);
        $history = new ShowMembershipHistoryHandler($repository);

        $creator(new CreateMembershipCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            '019eec93-9a11-7432-bd04-52306b2b3d90',
            '019eec93-9a11-7432-bd04-52306b2b3d91',
        ));

        $items = $history(new ShowMembershipHistoryQuery(
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
        ));

        self::assertCount(1, $items);
        self::assertSame('ACTIVE', $items[0]->toArray()['status']);
    }
}
