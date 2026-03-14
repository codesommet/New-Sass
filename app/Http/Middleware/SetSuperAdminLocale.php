<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSuperAdminLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('superadmin_locale', config('app.locale', 'fr'));

        if (in_array($locale, ['fr', 'en', 'ar'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
