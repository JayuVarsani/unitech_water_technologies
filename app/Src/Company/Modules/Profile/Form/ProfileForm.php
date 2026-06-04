<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Profile\Form;

use App\Models\Staff;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProfileForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $thumbImage;

    #[Validate]
    public $name;

    #[Validate]
    public $email;

    #[Validate(as: 'mobile number')]
    public $contactNumber;

    public function rules(): array
    {
        $this->resetErrorBag();

        return [
            'thumbImage' => ['nullable', 'file', 'image', 'max:'.config('media-library.max_file_size')],
            'name' => ['required', 'max:100', 'string'],
            'email' => ['required', 'email:filter', 'max:100', Rule::unique(Staff::class, 'email')->ignore($this->id, 'id')],
            'contactNumber' => ['required', 'numeric', 'digits_between:8,12', 'gt:0', Rule::unique(Staff::class, 'contact_number')->ignore($this->id, 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'contactNumber.unique' => 'This mobile number is already assigned to another company.',
            'email.unique' => 'This email is already used by another staff member.',
        ];
    }

    public function setStaff(Staff $staff): void
    {
        $this->id = $staff->id;
        $this->fill([
            'name' => $staff->name,
            'email' => $staff->email,
            'contactNumber' => $staff->contact_number,
        ]);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function update(Staff $staff): void
    {
        $this->validate();
        $staff->update($this->getValues());
        if ($this->thumbImage) {
            $staff->addMedia($this->thumbImage)->toMediaCollection('profile_image');
        }
        $this->reset('thumbImage');
    }

    protected function getValues(): array
    {
        return [
            'name' => ucfirst(trim((string) ($this->name ?? ''))),
            'email' => $this->email,
            'contact_number' => $this->contactNumber,
        ];
    }
}
