<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Auth;

use App\Models\Moderator;
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

    const guard = 'moderator';

    #[Validate]
    public string $email;

    public function save(): void
    {

        $this->validate();
        if ($this->ensureIsNotRateLimited('reset-password')) {
            throw ValidationException::withMessages(['email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey('reset-password'))])]);
        }
        $this->hitRateLimit('reset-password');
        $user = Moderator::where('email', $this->email)->first();
        if (StatusEnum::tryFrom($user?->status ?? '')?->is(StatusEnum::Active)) {
            $this->forgotPassword($this->email);
        }

        $this->reset();
        flashAlert(__('admin.reset-password.mail'), 'success');
    }

    public function rules(): array
    {
        return ['email' => ['required', 'email']];
    }

    public function render(): View
    {
        return view('admin::Auth.views.forgot-password')
            ->layout('panel::layout.auth', [
                'title' => __('admin.forgot-password.title'),
            ]);
    }
}
