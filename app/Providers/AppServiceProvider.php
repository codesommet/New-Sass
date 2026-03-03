<?php

namespace App\Providers;

use App\Services\System\DocumentNumberService;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DocumentNumberService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        Gate::policy(\App\Models\CRM\Customer::class, \App\Policies\CustomerPolicy::class);
        Gate::policy(\App\Models\Catalog\Product::class, \App\Policies\ProductPolicy::class);

        // Share appearance settings with the head partial for theme sync
        View::composer('backoffice.layout.partials.head', function ($view) {
            $appearance = [];
            if (TenantContext::check()) {
                $tenant = TenantContext::get();
                $appearance = $tenant->settings->modules_settings['appearance'] ?? [];
            }
            $view->with('appearanceSettings', $appearance);
        });
    }
}
