<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;

class SetTenantContext
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = TenantContext::get();

        if ($tenant) {
            // Load tenant settings (1:1 relationship)
            $settings = $tenant->settings;

            if ($settings) {
                // Set timezone
                if (isset($settings->localization_settings['timezone'])) {
                    config(['app.timezone' => $settings->localization_settings['timezone']]);
                    date_default_timezone_set($settings->localization_settings['timezone']);
                } elseif ($tenant->timezone) {
                    config(['app.timezone' => $tenant->timezone]);
                    date_default_timezone_set($tenant->timezone);
                }

                // Set locale/language
                if (isset($settings->localization_settings['language'])) {
                    app()->setLocale($settings->localization_settings['language']);
                }

                // Set currency
                if (isset($settings->account_settings['default_currency'])) {
                    config(['app.currency' => $settings->account_settings['default_currency']]);
                } elseif ($tenant->default_currency) {
                    config(['app.currency' => $tenant->default_currency]);
                }
            } else {
                // Fallback to tenant-level defaults
                if ($tenant->timezone) {
                    config(['app.timezone' => $tenant->timezone]);
                    date_default_timezone_set($tenant->timezone);
                }
                if ($tenant->default_currency) {
                    config(['app.currency' => $tenant->default_currency]);
                }
            }
        }

        return $next($request);
    }
}
