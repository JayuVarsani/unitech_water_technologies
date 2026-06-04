<?php

namespace App\Src\Company\Modules\Inquiry\Observers;

use App\Models\Inquiry;
use App\Utility\Enums\InquiryStatusEnum;
use App\Src\Company\Modules\Inquiry\Events\InquiryConfirmedEvent;

class InquiryObserver
{
    /**
     * Handle the Inquiry "created" event.
     */
    public function created(Inquiry $inquiry): void
    {
        //
    }

    /**
     * Handle the Inquiry "updated" event.
     */
    public function updated(Inquiry $inquiry): void
    {
        if ($inquiry->wasChanged('status') && $inquiry->status === InquiryStatusEnum::Confirmed->value) {
            InquiryConfirmedEvent::dispatch($inquiry);
        }
    }

    /**
     * Handle the Inquiry "deleted" event.
     */
    public function deleted(Inquiry $inquiry): void
    {
        //
    }

    /**
     * Handle the Inquiry "restored" event.
     */
    public function restored(Inquiry $inquiry): void
    {
        //
    }

    /**
     * Handle the Inquiry "force deleted" event.
     */
    public function forceDeleted(Inquiry $inquiry): void
    {
        //
    }
}
