<?php

declare(strict_types=1);

namespace Resources\Panel\Components\Sidebar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Modules extends Component
{
    public function __construct(public $modules = [])
    {
        $user = Auth::user();
        $modules = config('modules.'.$user->getMorphClass());

        if (Auth::user()->type === 'staff') {
            $finalModules = collect($modules)->map(function ($module) {
                $moduleUniqueName = $module['unique_name'];
                $hasViewPermission = $this->hasPermission($moduleUniqueName, 'view');

                if (! empty($module['children'])) {
                    $filteredChildren = collect($module['children'])->filter(function ($child) {
                        $childUniqueName = $child['unique_name'];

                        return $this->hasPermission($childUniqueName, 'view');
                    })->values()->all();

                    $module['children'] = $filteredChildren;
                    $hasViewPermission = $hasViewPermission || ! empty($filteredChildren);
                } else {
                    $module['children'] = [];
                }

                return $hasViewPermission ? array_filter($module) : null;
            })->filter()->values()->all();

            $this->modules = $finalModules;
        } else {
            $this->modules = $modules;
        }
    }

    public function hasPermission($moduleUniqueName, string $type = 'view', bool $abort = true): bool
    {
        return $moduleUniqueName ? auth()->user()->hasPermission($moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        return view('panel::components.sidebar.modules');
    }
}
