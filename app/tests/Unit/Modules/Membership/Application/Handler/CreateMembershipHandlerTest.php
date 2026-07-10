<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Membership\Application\Handler;

use App\Modules\Membership\Application\Command\CreateMembershipCommand;
use App\Modules\Membership\Application\Handler\CreateMembershipHandler;
use App\Modules\Membership\Domain\Exception\MembershipAlreadyExistsException;
use PHPUnit\Framework\TestCase;

final class CreateMembershipHandlerTest extends TestCase
{
    public function testItCreatesMembershipWithPrimaryGuardian(): void
    {
        $repository = new InMemoryMembershipRepository();
        $handler = new CreateMembershipHandler($repository);

        $response = $handler(new CreateMembershipCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            '019eec93-9a11-7432-bd04-52306b2b3d90',
            '019eec93-9a11-7432-bd04-52306b2b3d91',
        ));

        self::assertSame('019eec93-9a11-7432-bd04-52306b2b3d90', $response->toArray()['playerId']);
        self::assertSame('019eec93-9a11-7432-bd04-52306b2b3d91', $response->toArray()['primaryGuardianId']);
        self::assertSame('ACTIVE', $response->toArray()['status']);
        self::assertCount(1, $repository->memberships);
    }

    public function testItRejectsDuplicateActiveMembershipForTheSamePlayer(): void
    {
        $repository = new InMemoryMembershipRepository();
        $handler = new CreateMembershipHandler($repository);

        $command = new CreateMembershipCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            '019eec93-9a11-7432-bd04-52306b2b3d90',
            '019eec93-9a11-7432-bd04-52306b2b3d91',
        );

        $handler($command);

        $this->expectException(MembershipAlreadyExistsException::class);

        $handler($command);
    }
}
