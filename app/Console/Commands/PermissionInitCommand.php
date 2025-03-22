<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\PermissionService;
use Illuminate\Console\Command;

final class PermissionInitCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'permission:init';

    /**
     * @var string
     */
    protected $description = 'Command description';

    public function handle(PermissionService $permissionService): void
    {
        $permissionService->init();

        $this->info('Permission initialized successfully.');
    }
}
