<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Profile;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount() {}

    public function render(): View
    {
        return view('admin::Profile.views.dashboard')
            ->layout('panel::layout.app', [
                'title' => __('admin.dashboard'),
            ]);
    }
}
