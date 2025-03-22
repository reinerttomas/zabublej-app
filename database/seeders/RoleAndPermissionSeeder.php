<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\PermissionService;
use Illuminate\Database\Seeder;

final class RoleAndPermissionSeeder extends Seeder
{
    public function __construct(
        private readonly PermissionService $permissionService,
    ) {}

    public function run(): void
    {
        $this->permissionService->init();
    }
}
