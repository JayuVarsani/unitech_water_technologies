<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Amc;

use App\Models\Amc;
use App\Models\Customer;
use App\Src\Company\Modules\Amc\Form\AmcForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditAmc extends Component
{
    #[Locked]
    public Amc $amc;

    public AmcForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.amc';

    public function mount(Amc $amc): void
    {
        abort_if($amc->company_id !== Auth::user()->company_id, 404);

        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->amc = $amc->load('visitMonths');
        $this->form->setAmc($this->amc);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->amc);
        flashAlert(__('app.panel.update', ['name' => 'AMC']), 'success');
        $this->redirectRoute('company.amc.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => 'AMC']);
        $customers = Customer::where('company_id', Auth::user()->company_id)->orderBy('name')->get();

        if ($this->canEdit) {
            return view('company::Amc.views.form', [
                'title' => $title,
                'customers' => $customers,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['AMC', route('company.amc.index')],
                    [__('app.panel.edit'), route('company.amc.edit', $this->amc->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
