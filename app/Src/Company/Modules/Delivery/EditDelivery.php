<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Delivery;

use App\Models\Customer;
use App\Models\Delivery;
use App\Src\Company\Modules\Delivery\Form\DeliveryForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditDelivery extends Component
{
    use FormSessionTrait;
    use WithFileUploads;

    public $formSessionName = 'deliveryedit';

    #[Locked]
    public Delivery $delivery;

    public DeliveryForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.delivery';

    public function mount(Delivery $delivery): void
    {
        abort_if($delivery->company_id !== Auth::user()->company_id, 404);

        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->delivery = $delivery;
        $this->form->setDelivery($delivery);
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
        $this->form->update($this->delivery);
        flashAlert(__('app.panel.update', ['name' => 'Delivery']), 'success');
        $this->redirectRoute('company.delivery.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => 'Delivery']);
        $customers = Customer::where('company_id', Auth::user()->company_id)->orderBy('name')->get();

        if ($this->canEdit) {
            return view('company::Delivery.views.form', [
                'title' => $title,
                'customers' => $customers,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Deliveries', route('company.delivery.index')],
                    [__('app.panel.edit'), route('company.delivery.edit', $this->delivery->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
