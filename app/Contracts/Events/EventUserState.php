<?php

declare(strict_types=1);

namespace App\Contracts\Events;

use App\Models\EventAttendance;

interface EventUserState
{
    public function __construct(EventAttendance $eventUser);

    public function pending(): void;

    public function approved(): void;

    public function rejected(): void;
}
