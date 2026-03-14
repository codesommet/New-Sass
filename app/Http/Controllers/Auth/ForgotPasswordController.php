<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        $locale = app()->getLocale();
        $view = $locale !== 'fr' && view()->exists("{$locale}.auth.forgot-password")
            ? "{$locale}.auth.forgot-password"
            : 'auth.forgot-password';

        return view($view);
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
