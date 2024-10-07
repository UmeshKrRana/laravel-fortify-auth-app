<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        # Register
        Fortify::registerView(function () {
            return view('auth.register');
        });

        # Login View
        Fortify::loginView(function() {
            return view('auth.login');
        });

        # Forgot Password View
        Fortify::requestPasswordResetLinkView(function() {
            return view('auth.forgot-password');
        });

        # Reset Password View
        Fortify::resetPasswordView(function() {
            return view('auth.reset-password');
        });

        # Email Verification View
        Fortify::verifyEmailView(function() {
            return view('auth.verify-email');
        });

        # Password Confirm View
        Fortify::confirmPasswordView(function() {
            return view('auth.confirm-password');
        });

        # Two Factor Challenge View
        Fortify::twoFactorChallengeView(function(Request $request) {
            $recovery = $request->get('recovery', false);
            return view('auth.two-factor-challenge', compact('recovery'));
        });
    }
}
