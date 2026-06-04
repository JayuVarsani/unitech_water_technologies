<?php

namespace App\Src\Company\Modules\OrderJob\Observers;

use App\Models\OrderJob;
use App\Utility\Enums\OrderJobStatusEnum;
use App\Utility\Enums\OrderStatusEnum;

class OrderJobObserver
{
    /**
     * Handle the Inquiry "created" event.
     */
    public function created(OrderJob $orderJob): void
    {
        $this->setOrderStatus($orderJob);
    }

    /**
     * Handle the Inquiry "updated" event.
     */
    public function updated(OrderJob $orderJob): void
    {
        if ($orderJob->wasChanged('status')) {
            $this->setOrderStatus($orderJob);
        }
    }

    /**
     * Handle the Inquiry "deleted" event.
     */
    public function deleted(OrderJob $orderJob): void
    {
        //
    }

    /**
     * Handle the Inquiry "restored" event.
     */
    public function restored(OrderJob $orderJob): void
    {
        //
    }

    /**
     * Handle the Inquiry "force deleted" event.
     */
    public function forceDeleted(OrderJob $orderJob): void
    {
        //
    }

    protected function setOrderStatus(OrderJob $orderJob): void
    {
        $order = $orderJob->order;
        if (! $order) {
            return;
        }

        $totalJobs = $order->orderJobs()->count();
        if ($totalJobs === 0) {
            return;
        }
        $cancelledJobs = $order->orderJobs()
            ->where('status', OrderJobStatusEnum::Cancelled->value)
            ->count();

        if ($cancelledJobs === $totalJobs) {
            $order->update(['status' => OrderStatusEnum::Cancelled->value]);
            return;
        }

        $activeJobs = $totalJobs - $cancelledJobs;

        $completedJobs = $order->orderJobs()
            ->where('status', OrderJobStatusEnum::Completed->value)
            ->count();

        if ($activeJobs > 0 && $completedJobs === $activeJobs) {
            $order->update(['status' => OrderStatusEnum::Completed->value]);
            return;
        }

        $printedJobs = $order->orderJobs()
            ->where('status', OrderJobStatusEnum::Printed->value)
            ->count();

        if ($activeJobs > 0 && $printedJobs === $activeJobs) {
            $order->update(['status' => OrderStatusEnum::Printed->value]);
            return;
        }

        $designJobs = $order->orderJobs()
            ->where('status', OrderJobStatusEnum::Design->value)
            ->count();

        if ($designJobs > 0) {
            $order->update(['status' => OrderStatusEnum::Confirmed->value]);
        }
    }
}
