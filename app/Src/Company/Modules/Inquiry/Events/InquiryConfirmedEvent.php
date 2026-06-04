<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry\Events;

use App\Models\Inquiry;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class InquiryConfirmedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Inquiry $inquiry)
    {
        //
    }
}
