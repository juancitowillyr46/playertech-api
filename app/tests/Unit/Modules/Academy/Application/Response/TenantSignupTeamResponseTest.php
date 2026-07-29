<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Academy\Application\Response;

use App\Modules\Academy\Application\Response\TenantSignupTeamResponse;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class TenantSignupTeamResponseTest extends TestCase
{
    public function testItSerializesTheOnboardingTeamWithCategoryName(): void
    {
        $team = Team::create(
            TeamId::generate(),
            AcademyId::generate(),
            CategoryId::generate(),
            new Name('Sub 4 A'),
            AuditTrail::create('system'),
        );

        $response = TenantSignupTeamResponse::fromTeam($team, 'Sub 4');

        self::assertSame([
            'id' => $team->id()->value(),
            'academyId' => $team->academyId()->value(),
            'categoryId' => $team->categoryId()->value(),
            'categoryName' => 'Sub 4',
            'name' => 'Sub 4 A',
            'status' => $team->status()->value(),
        ], $response->toArray());
    }
}
