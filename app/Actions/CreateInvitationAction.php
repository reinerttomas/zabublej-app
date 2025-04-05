<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Invitation;
use App\Notifications\Invitation\InvitationRegisterNotification;
use App\ValueObjects\InvitationPayload;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class CreateInvitationAction
{
    /**
     * @param  array{ name: string, email: string, role: \App\Enums\Role, description: string|null }  $data
     *
     * @throws Throwable
     */
    public function execute(array $data): Invitation
    {
        return DB::transaction(function () use ($data): Invitation {
            $invitation = Invitation::create([
                'email' => $data['email'],
                'payload' => InvitationPayload::from($data),
                'expires_at' => now()->addHour(),
            ]);

            $invitation->notify(new InvitationRegisterNotification($invitation));

            return $invitation;
        });
    }
}
