<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission->value]);
        }

        foreach (RoleEnum::cases() as $roleEnum) {
            $role = Role::create(['name' => $roleEnum->value]);

            $this->syncPermissionsToRole($role);
        }
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = match ($role->name) {
            RoleEnum::Admin->value => [
                PermissionEnum::ViewAnyUser,
                PermissionEnum::UpdateUser,
                PermissionEnum::DeleteUser,
                PermissionEnum::ViewAnyEvent,
                PermissionEnum::ViewEventPrice,
                PermissionEnum::CreateEvent,
                PermissionEnum::UpdateEvent,
                PermissionEnum::DeleteEvent,
            ],
            RoleEnum::Staff->value => [
                PermissionEnum::ViewEvent,
            ],
            default => [],
        };

        $role->syncPermissions($permissions);
    }
}
