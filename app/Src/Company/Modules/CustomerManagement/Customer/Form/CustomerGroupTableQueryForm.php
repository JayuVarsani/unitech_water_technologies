<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\Customer\Form;

use App\Utility\livewire\TableForm;
use Livewire\Attributes\Url;

class CustomerGroupTableQueryForm extends TableForm
{
    #[Url('customer-group')]
    public string $customerGroupId = '';
}
