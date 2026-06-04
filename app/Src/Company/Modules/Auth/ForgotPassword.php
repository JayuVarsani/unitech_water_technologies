<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Auth;

use App\Models\Staff;
use App\Utility\Enums\StatusEnum;
use App\Utility\Traits\AuthenticateTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    use AuthenticateTrait;

    const guard = 'company';

    #[Validate]
    public string $email;

    public function save(): void
    {
        $this->validate();
        if ($this->ensureIsNotRateLimited('reset-password')) {
            throw ValidationException::withMessages(['email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey('reset-password'))])]);
        }
        $this->hitRateLimit('reset-password');
        $user = Staff::where('email', $this->email)->first();

        if (StatusEnum::tryFrom((int) ($user?->status ?? 0))?->is(StatusEnum::Active)) {
            $this->forgotPasswordCompany($this->email);
        }
        $this->reset();
        flashAlert(__('company.reset-password.mail'), 'success');
    }

    public function rules(): array
    {
        return ['email' => ['required', 'email']];
    }

    public function render(): View
    {
        return view('company::Auth.views.forgot-password')
            ->layout('panel::layout.auth', [
                'title' => __('company.forgot-password.title'),
            ]);
    }
}
