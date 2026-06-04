<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Order\Form;

use Livewire\Attributes\Url;
use App\Utility\livewire\TableForm;

class OrderTableQueryForm extends TableForm
{
    #[Url]
    public $status;

    #[Url]
    public $startDate;

    #[Url]
    public $endDate;
}
