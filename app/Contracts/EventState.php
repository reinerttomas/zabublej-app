<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Event;

interface EventState
{
    public function __construct(Event $event);

    public function published(): void;

    public function cancelled(): void;

    public function completed(): void;
}
