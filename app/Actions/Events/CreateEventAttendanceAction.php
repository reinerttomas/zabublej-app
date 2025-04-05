<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Enums\EventAttendanceStatus;
use App\Enums\Permission;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use App\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final readonly class CreateEventAttendanceAction
{
    public function execute(Event $event, User $user): EventAttendance
    {
        // Admins automatically confirm attendance
        if (Auth::userOrFail()->hasPermissionTo(Permission::UpdateEventAttendance)) {
            $status = EventAttendanceStatus::Confirmed;
        }

        $status ??= EventAttendanceStatus::Pending;

        return DB::transaction(fn(): EventAttendance => EventAttendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => $status,
        ]));
    }
}
