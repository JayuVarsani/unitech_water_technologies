<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Auth;

use App\Utility\Traits\AuthenticateTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPassword extends Component
{
    use AuthenticateTrait;

    const guard = 'moderator';

    #[Url('email')]
    #[Validate]
    public string $email;

    #[Url('token')]
    public string $token;

    #[Validate]
    public string $password;

    #[Validate]
    public string $confirmPassword;

    public function submit(): void
    {
        $this->validate();
        $status = $this->resetPassword(['email' => $this->email, 'password' => $this->password, 'token' => $this->token]);
        if (Password::PASSWORD_RESET) {
            flashAlert(__($status), 'success');
            $this->redirectRoute('admin.auth.login');
        } else {
            flashAlert(__($status), 'danger');
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'max:100', PasswordRule::min(6)->letters()->mixedCase()->numbers()->symbols()],
            'confirmPassword' => ['required', 'same:password'],
        ];
    }

    public function render(): View
    {
        return view('admin::Auth.views.reset-password')
            ->layout('panel::layout.auth', [
                'title' => __('admin.reset-password.title'),
            ]);
    }
}
