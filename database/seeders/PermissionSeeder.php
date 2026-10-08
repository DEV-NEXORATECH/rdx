<?php

namespace Database\Seeders;

use App\Domains\Admin\Support\PermissionRegistry;
use App\Domains\Identity\Models\Permission;
use App\Domains\Identity\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = PermissionRegistry::all();

        foreach ($allPermissions as $permission => $label) {
            Permission::query()->updateOrCreate(['name' => $permission], ['label' => $label]);
        }

        $matrix = PermissionRegistry::roleMatrix();

        foreach (PermissionRegistry::roleLabels() as $roleName => $roleLabel) {
            $role = Role::query()->updateOrCreate(['name' => $roleName], ['label' => $roleLabel]);

            $permissions = $matrix[$roleName] ?? [];

            $valid = array_values(array_intersect($permissions, array_keys($allPermissions)));

            $role->syncPermissions($valid);
        }
    }
}
