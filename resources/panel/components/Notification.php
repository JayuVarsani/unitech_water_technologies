<?php

declare(strict_types=1);

namespace Resources\Panel\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notification extends Component
{
    public int $unReadNotificationCount = 0;

    public Collection $notifications;

    public function mount(): void
    {
        $this->notifications = collect();
        $this->hasNotifications();
    }

    public function render(): View
    {
        return view('panel::components.notification');
    }

    public function hasNotifications(): void
    {
        $user = Auth::user();
        $this->unReadNotificationCount = $user->unreadNotifications()->count();
        //        if ($this->unReadNotificationCount) {
        $this->notifications = cacheCallBack('notification_'.$user->id.$this->unReadNotificationCount, function () use ($user) {
            return $user->notifications;
        });
        //        }
    }

    public function readAllNotifications(): void
    {
        $user = Auth::user();
        if ($this->unReadNotificationCount) {
            $user->unreadNotifications()->update(['read_at' => now()]);
            $user->unreadNotifications()->where('created_at', '<=', now()->subWeek())->delete();
        }
        $this->unReadNotificationCount = 0;
    }
}
