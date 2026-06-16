<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Amc;

use App\Models\Customer;
use App\Src\Company\Modules\Amc\Form\AmcForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateAmc extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'amc';

    public AmcForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.amc';

    public function mount(): void
    {
        $this->canCreate = $this->hasPermission(type: 'create');

        if (empty($this->form->fromDate)) {
            $this->form->fromDate = AmcForm::defaultFromDate();
        }

        if (empty($this->form->toDate)) {
            $this->form->toDate = AmcForm::defaultToDate();
        }
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createAmc();
        $this->purgeFromSession();
        flashAlert(__('app.panel.store', ['name' => 'AMC']), 'success');
        $this->redirectRoute('company.amc.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => 'AMC']);
        $customers = Customer::where('company_id', Auth::user()->company_id)->orderBy('name')->get();

        if ($this->canCreate) {
            return view('company::Amc.views.form', [
                'title' => $title,
                'customers' => $customers,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['AMC', route('company.amc.index')],
                    [__('app.panel.create'), route('company.amc.create')],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
