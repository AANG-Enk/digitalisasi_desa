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
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            $email = (string) $request->username;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        //register
        Fortify::registerView(function () {
            return view('auth.register');
        });

        //login
        Fortify::loginView(function () {
            return view('auth.login');
        });

        //forgot
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        //reset
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        //veryfied email
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ],[
                'username.required'         => 'Username harus diisi',
                'username.string'           => 'Username tidak valid',
                'password.required'         => 'Password harus diisi',
                'password.string'           => 'Password tidak valid',
            ]);

            $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' :(is_numeric($request->username) ? 'nik' : 'username');

            if(!User::where($loginField, $request->username)->first()){
                session()->flash('error', 'User tidak ditemukan.');
                return redirect()->back();
            }

            if (Auth::attempt([$loginField => $request->username, 'password' => $request->password])) {
                return Auth::user();
            }

            session()->flash('error', 'Password salah.');
            return null;
        });
    }
}
