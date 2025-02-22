<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Auth\LogoutAction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class DeleteUserAction
{
    public function __construct(
        private LogoutAction $logout,
    ) {}

    public function execute(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $this->logout->execute();

            $user->delete();
        });
    }
}
