<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Dev setup commands (run in order):
     *   php artisan config:clear
     *   php artisan cache:clear
     *   php artisan route:clear
     *   php artisan view:clear
     *   php artisan migrate          (safe — all migrations are idempotent)
     *   php artisan db:seed           (safe — uses firstOrCreate everywhere)
     *
     * For a full reset (DESTROYS ALL DATA):
     *   php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        $this->call([
            // 1) Plans (global, no tenant FK)
            PlanSeeder::class,

            // 2) Permissions (~120 permission strings)
            PermissionSeeder::class,

            // 3) Roles (super_admin + global role templates)
            RoleSeeder::class,

            // 4) Demo tenant with users, role assignments, subscription (dev only)
            DemoTenantSeeder::class,
        ]);
    }
}
