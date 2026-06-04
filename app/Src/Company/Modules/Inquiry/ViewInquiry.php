<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry;

use App\Models\Inquiry;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ViewInquiry extends Component
{
    public $canView;

    public Inquiry $inquiry;

    protected string $moduleUniqueName = 'company.inquiry';

    public function mount(Inquiry $inquiry)
    {
        $this->canView = $this->hasPermission(type: 'view');

        $this->inquiry = $inquiry;

        $this->inquiry->load('customer', 'inquiryJobs');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        if ($this->canView) {
            return view('company::Inquiry.views.view', [
            'title' => __('app.panel.view_name', ['name' => __('company.inquiry')]),
            ])->layout('panel::layout.print', [
                'title' => __('app.panel.view_name', ['name' => __('company.inquiry')]),
            ]);
        } else {
            abort(403);
        }
    }
}
