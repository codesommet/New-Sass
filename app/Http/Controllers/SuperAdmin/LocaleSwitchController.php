<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleSwitchController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'locale' => ['required', 'string', 'in:fr,en,ar'],
        ]);

        $locale = $request->input('locale');

        session(['superadmin_locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
