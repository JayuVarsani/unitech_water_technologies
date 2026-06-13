<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Delivery;

use App\Models\Delivery;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewDelivery extends Component
{
    public Delivery $delivery;

    public $canView;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.delivery';

    public function mount(Delivery $delivery): void
    {
        abort_if($delivery->company_id !== Auth::user()->company_id, 404);

        $this->delivery = $delivery;
        $this->canView = $this->hasPermission(type: 'view');
        $this->canEdit = $this->hasPermission(type: 'edit');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        $title = __('app.panel.view_name', ['name' => 'Delivery']);

        if ($this->canView) {
            return view('company::Delivery.views.view', [
                'title' => $title,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Deliveries', route('company.delivery.index')],
                    [__('app.panel.view'), route('company.delivery.view', $this->delivery->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
