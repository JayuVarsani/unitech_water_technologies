<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCard\Form;

use App\Utility\livewire\TableForm;
use Livewire\Attributes\Url;

class JobCardTableQueryForm extends TableForm
{
    #[Url]
    public $job_status;

    #[Url]
    public $customer_id_filter;

    #[Url]
    public $startDate;

    #[Url]
    public $endDate;
}
