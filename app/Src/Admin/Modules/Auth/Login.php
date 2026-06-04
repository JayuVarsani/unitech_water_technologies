<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Auth;

use App\Utility\Enums\StatusEnum;
use App\Utility\Traits\AuthenticateTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    use AuthenticateTrait;

    const guard = 'moderator';

    #[Validate]
    public string $email;

    #[Validate]
    public string $password;

    public function auth(): void
    {
        $this->validate();
        if ($this->ensureIsNotRateLimited()) {
            throw ValidationException::withMessages(['email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey())])]);
        }
        $isLogin = $this->login(['email' => $this->email, 'password' => $this->password]);
        if (! $isLogin) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }
        $user = Auth::guard('moderator')->user();
        if (StatusEnum::from($user->status)?->isActive()) {
            flashAlert(__('admin.login.to_dashboard'), 'success');
            $this->redirect(route('admin.company.index'));
        } else {
            Auth::guard('moderator')->logout();
            throw ValidationException::withMessages(['email' => 'Your account is disabled by administrator']);
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function render(): View
    {
        return view('admin::Auth.views.login')
            ->layout('panel::layout.auth', ['title' => __('admin.login.title')]);
    }
}
