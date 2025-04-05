<?php

declare(strict_types=1);

namespace App\ValueObjects;

final readonly class EventCapacity
{
    public function __construct(
        public ?int $maxWorkers,
        public int $pendingCount,
        public int $approvedCount,
    ) {}

    /**
     * Vrátí celkový počet obsazených míst (potvrzení + čekající)
     */
    public function getOccupiedCount(): int
    {
        return $this->approvedCount + $this->pendingCount;
    }

    /**
     * Vrátí počet volných míst (celkem)
     */
    public function getFreeCapacity(): int
    {
        if ($this->maxWorkers === null) {
            return 0;
        }

        return max(0, $this->maxWorkers - $this->getOccupiedCount());
    }

    public function hasFreeCapacity(): bool
    {
        return $this->getFreeCapacity() > 0;
    }
}
