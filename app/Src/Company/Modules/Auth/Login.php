<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Auth;

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

    const guard = 'company';

    #[Validate (as: "mobile number")]
    public string $contactNumber;

    #[Validate]
    public string $password;

    public function auth(): void
    {
        $this->validate();
        if ($this->ensureIsNotRateLimited()) {
            // throw ValidationException::withMessages(['contactNumber' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey())])]);
            throw ValidationException::withMessages(['contactNumber' => __('auth.failed')]);
        }
        $isLogin = $this->login(['contact_number' => trim($this->contactNumber), 'password' => $this->password ? trim($this->password) : null]);
        if (! $isLogin) {
            throw ValidationException::withMessages(['contactNumber' => __('auth.failed')]);
        }
        $user = Auth::guard('company')->user();
        if (StatusEnum::from($user->status)?->isActive()) {
            flashAlert(__('company.login.to_dashboard'), 'success');
            $this->redirect(route('company.dashboard'));
        } else {
            Auth::guard('company')->logout();
            throw ValidationException::withMessages(['contactNumber' => 'Your account is disabled by administrator']);
        }
    }

    public function rules(): array
    {
        return [
            'contactNumber' => ['required'],
            'password' => ['required'],
        ];
    }

    public function render(): View
    {
        return view('company::Auth.views.login')
            ->layout('panel::layout.auth', ['title' => __('company.login.title')]);
    }
}
