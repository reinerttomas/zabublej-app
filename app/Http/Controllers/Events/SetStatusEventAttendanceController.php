<?php

declare(strict_types=1);

namespace App\Http\Controllers\Events;

use App\Enums\EventAttendanceStatus;
use App\Models\EventAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final readonly class SetStatusEventAttendanceController
{
    public function __invoke(EventAttendance $eventAttendance, int $status): RedirectResponse
    {
        Gate::authorize('update', $eventAttendance);

        $status = EventAttendanceStatus::tryFrom($status);

        if ($status === null) {
            return redirect()->route('event-attendances.index')
                ->with('error', 'Neplatný status přihlášky.');
        }

        if (! $eventAttendance->status->isPending()) {
            return redirect()->route('event-attendances.index')
                ->with('warning', 'Tato přihláška již byla zpracována.');
        }

        $eventAttendance->setStatus($status);
        $eventAttendance->save();

        $message = $status->isConfirm()
            ? sprintf('Přihláška byla schválena: %s', $eventAttendance->event->name)
            : sprintf('Přihláška byla zamítnuta: %s', $eventAttendance->event->name);

        return redirect()->route('event-attendances.index', ['status' => $status->value])
            ->with('success', $message);
    }
}
