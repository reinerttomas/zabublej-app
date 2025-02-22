<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Models\Event;

final readonly class CreateEventAction
{
    public function execute(array $data): Event
    {
        return Event::create($data);
    }
}
