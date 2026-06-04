<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChangePassword extends Component
{
    #[Validate]
    public string $currentPassword;

    #[Validate]
    public string $password;

    #[Validate]
    public string $confirmPassword;

    public function resetPassword(): void
    {
        $this->validate();
        $user = request()->user();
        if (! Hash::check($this->currentPassword, $user->password)) {
            throw ValidationException::withMessages(['currentPassword' => __('auth.password')]);
        }
        $user->update(['password' => $this->password]);
        $this->reset();
        flashAlert(__('admin.change-password.updated'), 'success');
    }

    public function rules(): array
    {
        return [
            'currentPassword' => ['required'],
            'password' => ['required', 'max:100', Password::min(6)->letters()->mixedCase()->numbers()->symbols()],
            'confirmPassword' => ['required', 'same:password'],
        ];
    }

    public function render(): View
    {
        return view('admin::Profile.views.change-password')
            ->layout('panel::layout.app', [
                'title' => __('admin.change-password.title'),
                'breadcrumb' => [[__('admin.change-password.title'), route('admin.profile.change-password')]],
            ]);
    }
}
