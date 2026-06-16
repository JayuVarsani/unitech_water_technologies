<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit;

use App\Utility\livewire\TableForm;
use Livewire\Attributes\Url;

class VisitTableForm extends TableForm
{
    #[Url]
    public $startDate;

    #[Url]
    public $endDate;
}
