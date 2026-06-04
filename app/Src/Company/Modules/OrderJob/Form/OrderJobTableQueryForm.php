<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\OrderJob\Form;

use Livewire\Attributes\Url;
use App\Utility\livewire\TableForm;

class OrderJobTableQueryForm extends TableForm
{
    #[Url]
    public $status;

    #[Url]
    public $startDate;

    #[Url]
    public $endDate;

    #[Url]
    public $orderId;
}
