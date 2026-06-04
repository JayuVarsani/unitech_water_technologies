<?php

declare(strict_types=1);

namespace App\Src\Company\Infrastructure;

use App\Src\Company\Modules\Inquiry\Events\InquiryConfirmedEvent;
use App\Src\Company\Modules\Inquiry\Events\Listeners\CreateOrderListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as MainEventServiceProvider;

class EventServiceProvider extends MainEventServiceProvider
{
    protected $listen = [
        InquiryConfirmedEvent::class => [
            CreateOrderListener::class,
        ],
    ];
}
