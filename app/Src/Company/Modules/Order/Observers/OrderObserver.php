<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Order\Observers;

use App\Models\Order;
use App\Utility\Enums\OrderJobStatusEnum;
use App\Utility\Enums\OrderStatusEnum;

class OrderObserver
{
    public function created(Order $order): void
    {
        $this->syncJobsToOrderStatus($order);
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            $this->syncJobsToOrderStatus($order);
        }
    }

    public function deleted(Order $order): void {}

    public function restored(Order $order): void {}

    public function forceDeleted(Order $order): void {}

    protected function syncJobsToOrderStatus(Order $order): void
    {
        $jobStatus = match ($order->status) {
            OrderStatusEnum::Cancelled->value => OrderJobStatusEnum::Cancelled->value,
            OrderStatusEnum::Completed->value => OrderJobStatusEnum::Completed->value,
            // OrderStatusEnum::Confirmed->value => OrderJobStatusEnum::Design->value,
            // OrderStatusEnum::Printed->value => OrderJobStatusEnum::Printed->value,
            default => null,
        };

        if ($jobStatus === null || $order->orderJobs()->count() === 0) {
            return;
        }
        $values = [
            'status' => $jobStatus,
        ];
        if ($jobStatus === OrderJobStatusEnum::Cancelled->value) {
            $values['cancellation_reason'] = $order->cancellation_reason;
        }
        $order->orderJobs()->update($values);
    }
}
