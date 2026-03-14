<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendVerificationRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function showVerificationNotice()
    {
        // Redirect to dashboard if already verified
        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            return redirect(route('dashboard'));
        }

        $locale = app()->getLocale();
        $view = $locale !== 'fr' && view()->exists("{$locale}.auth.verify-email")
            ? "{$locale}.auth.verify-email"
            : 'auth.verify-email';

        return view($view);
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect(route('dashboard'))->with('success', 'Adresse e-mail vérifiée avec succès.');
    }

    public function resend(ResendVerificationRequest $request)
    {
        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            return back()->with('success', 'Votre adresse e-mail est déjà vérifiée.');
        }

        auth()->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Un lien de vérification a été envoyé à votre adresse e-mail. Il expire dans 1 heure.');
    }
}
