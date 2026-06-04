<?php

declare(strict_types=1);

namespace Resources\Panel\Components;

use App\Models\Moderator;
use App\Models\Staff;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LogoutLink extends Component
{
    public string $variant = 'sidebar';

    public function logout(): void
    {
        $guard = match (Auth::user()->getMorphClass()) {
            Moderator::class => 'moderator',
            Staff::class => 'company',
            default => 'company',
        };

        Auth::guard($guard)->logout();
        session()->invalidate();
        session()->regenerateToken();

        $loginRoute = $guard === 'moderator' ? 'admin.auth.login' : 'company.auth.login';

        if ($guard === 'moderator') {
            flashAlert(__('admin.logout.to_login'), 'success');
        }

        $this->redirectRoute($loginRoute);
    }

    public function render(): View
    {
        $label = Auth::user()->getMorphClass() === Moderator::class
            ? __('admin.logout.title')
            : __('company.logout.title');

        return view('panel::components.logout-link', [
            'label' => $label,
        ]);
    }
}
