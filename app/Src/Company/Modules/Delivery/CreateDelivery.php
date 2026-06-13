<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Delivery;

use App\Models\Customer;
use App\Src\Company\Modules\Delivery\Form\DeliveryForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateDelivery extends Component
{
    use FormSessionTrait;
    use WithFileUploads;

    public $formSessionName = 'delivery';

    public DeliveryForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.delivery';

    public function mount(): void
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        if (empty($this->form->deliveryDate)) {
            $this->form->deliveryDate = now()->format('Y-m-d');
        }
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function setEntryType(string $type): void
    {
        $this->form->setEntryType($type);
    }

    public function save(): void
    {
        $this->form->createDelivery();
        $this->purgeFromSession();
        flashAlert(__('app.panel.store', ['name' => 'Delivery']), 'success');
        $this->redirectRoute('company.delivery.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => 'Delivery']);
        $customers = Customer::where('company_id', Auth::user()->company_id)->orderBy('name')->get();

        if ($this->canCreate) {
            return view('company::Delivery.views.form', [
                'title' => $title,
                'customers' => $customers,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Deliveries', route('company.delivery.index')],
                    [__('app.panel.create'), route('company.delivery.create')],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
