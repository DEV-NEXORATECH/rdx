<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('production')) {
            $this->call(AdminUserSeeder::class);
        }

        $this->call(PermissionSeeder::class);
        $this->call(AccountSeeder::class);
    }
}
