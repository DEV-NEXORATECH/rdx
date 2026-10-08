<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.seed_admin_email', env('SEED_ADMIN_EMAIL', 'admin@jobfinance.test'));

        if ($email === null || $email === '') {
            return;
        }

        $password = config('app.seed_admin_password', env('SEED_ADMIN_PASSWORD', 'password'));

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'is_active' => true,
            ],
        );

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'is_active' => true,
            ],
        );

        $superAdminRole = Role::query()->firstOrCreate(['name' => 'super_admin'], ['label' => 'Super Admin']);
        $admin->syncRoles([$superAdminRole->name]);
    }
}
