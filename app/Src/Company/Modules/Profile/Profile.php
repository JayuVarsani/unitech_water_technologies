<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Profile;

use App\Models\Staff;
use App\Src\Company\Modules\Profile\Form\ProfileForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    #[Locked]
    public Staff $staff;

    public ProfileForm $form;

    public function mount(): void
    {
        $this->staff = Auth::user();
        $this->form->setStaff($this->staff);
    }

    public function save(): void
    {
        $this->form->update($this->staff);
        flashAlert(__('company.profile.details_updated'), 'success');
        $this->redirectRoute('company.dashboard');
    }

    public function render(): View
    {
        return view('company::Profile.views.profile', [
            'existingProfileImage' => $this->staff->getFirstMediaUrl('profile_image'),
        ])->layout('panel::layout.app', [
            'title' => __('company.profile.title'),
            'breadcrumb' => [[__('company.profile.title'), route('company.profile.index')]],
        ]);
    }
}
