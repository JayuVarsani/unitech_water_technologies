<?php

declare(strict_types=1);

namespace App\Utility\Traits;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

trait AuthenticateTrait
{
    public function login(array $credentials = [], bool $rememberMe = true): bool
    {
        $isLogin = Auth::guard(self::guard)->attempt($credentials, $rememberMe);
        if ($isLogin) {
            RateLimiter::cleanRateLimiterKey($this->throttleKey());
        } else {
            $this->hitRateLimit();
        }

        return $isLogin;
    }

    public function sessionLogout(Request $request): void
    {
        Auth::guard(self::guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function forgotPassword($email): string
    {
        $this->cleanRateLimiterKey('reset-password');

        ResetPassword::createUrlUsing(fn ($user, string $token) => route('admin.auth.reset-password', ['token' => $token, 'email' => $email]));

        return Password::broker($this->getBroker())->sendResetLink(['email' => $email]);
    }

    public function forgotPasswordCompany($email): string
    {

        $this->cleanRateLimiterKey('reset-password');

        ResetPassword::createUrlUsing(fn ($user, string $token) => route('company.auth.reset-password', ['token' => $token, 'email' => $email]));

        return Password::broker($this->getBroker())->sendResetLink(['email' => $email]);
    }

    public function resetPassword(array $credential)
    {
        return Password::broker($this->getBroker())->reset($credential, function ($user, string $password) {
            $user->forceFill(['password' => $password])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });
    }

    protected function ensureIsNotRateLimited($key = 'login'): bool
    {
        return RateLimiter::tooManyAttempts($this->throttleKey($key), 20);
    }

    protected function throttleKey($key = 'login'): string
    {
        return self::guard.'-'.$key.'-'.request()->ip();
    }

    protected function getBroker(): string
    {
        return match (self::guard) {
            'moderator' => 'moderators',
            'company' => 'companies',
        };
    }

    private function cleanRateLimiterKey($key): void
    {
        RateLimiter::cleanRateLimiterKey($this->throttleKey($key));
    }

    private function hitRateLimit($key = 'login'): void
    {
        RateLimiter::hit($this->throttleKey($key), 60 * 60);
    }
}
