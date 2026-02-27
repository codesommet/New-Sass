<?php

namespace Database\Seeders;

use App\Models\Billing\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed the plans table with default SaaS plans.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'code' => 'free',
                'interval' => 'month',
                'price' => 0.00,
                'currency' => 'MAD',
                'trial_days' => 0,
                'is_active' => true,
                'features' => [
                    'max_invoices' => 50,
                    'max_users' => 1,
                    'modules' => ['sales', 'crm'],
                ],
            ],
            [
                'name' => 'Starter',
                'code' => 'starter',
                'interval' => 'month',
                'price' => 99.00,
                'currency' => 'MAD',
                'trial_days' => 14,
                'is_active' => true,
                'features' => [
                    'max_invoices' => 500,
                    'max_users' => 3,
                    'modules' => ['sales', 'crm', 'inventory', 'purchases', 'reports'],
                ],
            ],
            [
                'name' => 'Pro',
                'code' => 'pro',
                'interval' => 'month',
                'price' => 299.00,
                'currency' => 'MAD',
                'trial_days' => 14,
                'is_active' => true,
                'features' => [
                    'max_invoices' => -1, // Unlimited
                    'max_users' => 10,
                    'modules' => ['sales', 'crm', 'inventory', 'purchases', 'finance', 'reports', 'pro'],
                ],
            ],
            [
                'name' => 'Enterprise',
                'code' => 'enterprise',
                'interval' => 'month',
                'price' => 0.00, // Custom pricing
                'currency' => 'MAD',
                'trial_days' => 0,
                'is_active' => true,
                'features' => [
                    'max_invoices' => -1, // Unlimited
                    'max_users' => -1,    // Unlimited
                    'modules' => ['sales', 'crm', 'inventory', 'purchases', 'finance', 'reports', 'pro', 'api'],
                    'custom_pricing' => true,
                ],
            ],
        ];

        foreach ($plans as $planData) {
            Plan::firstOrCreate(
                ['code' => $planData['code']],
                $planData
            );
        }
    }
}
