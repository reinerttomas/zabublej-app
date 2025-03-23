<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final readonly class PermissionService
{
    public function init(): void
    {
        DB::transaction(function (): void {
            foreach (PermissionEnum::cases() as $permission) {
                Permission::firstOrCreate(['name' => $permission->value]);
            }

            foreach (RoleEnum::cases() as $roleEnum) {
                $role = Role::firstOrCreate(['name' => $roleEnum->value]);

                $this->syncPermissionsToRole($role);
            }
        });
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = match ($role->name) {
            RoleEnum::Admin->value => [
                PermissionEnum::ViewAnyUser,
                PermissionEnum::InviteUser,
                PermissionEnum::UpdateUser,
                PermissionEnum::DeleteUser,
                PermissionEnum::ViewAnyEvent,
                PermissionEnum::ViewPriceEvent,
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
