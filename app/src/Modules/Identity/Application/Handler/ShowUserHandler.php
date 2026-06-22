<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Query\ShowUserQuery;
use App\Modules\Identity\Application\Response\UserResponse;

final readonly class ShowUserHandler extends AbstractUserHandler
{
    public function __invoke(ShowUserQuery $query): UserResponse
    {
        $user = $this->requireUser($query->userId, $query->academyId);

        return UserResponse::fromUser($user);
    }
}
