<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Models\Event;

final readonly class UpdateEventAction
{
    public function execute(Event $event, array $data): void
    {
        $event->fill($data);
        $event->save();
    }
}
