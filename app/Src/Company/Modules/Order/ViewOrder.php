<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Order;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ViewOrder extends Component
{
    public $canView;

    public $canEdit;

    public Order $order;

    protected string $moduleUniqueName = 'company.order';

    public function mount(Order $order)
    {
        $this->order = $order;
        
        $this->order->load('customer', 'orderJobs');
        
        $this->canView = $this->hasPermission(type: 'view');
        
        $this->canEdit = $this->hasPermission(type: 'edit');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        if ($this->canView) {
            return view('company::Order.views.view', [
                'title' => __('app.panel.view_name', ['name' => __('company.order')]),
            ])->layout('panel::layout.app', [
                'title' => __('app.panel.view_name', ['name' => __('company.order')]),
                'breadcrumb' => [
                    [__('company.order'), route('company.order.index')],
                    [__('app.panel.view'), route('company.order.view', $this->order->id)],
                ],
            ]);
        } else {
            abort(403);
        }
    }
}
