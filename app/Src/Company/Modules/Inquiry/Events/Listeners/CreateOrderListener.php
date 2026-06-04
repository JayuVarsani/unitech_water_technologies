<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry\Events\Listeners;

use App\Models\Order;
use App\Utility\Enums\OrderStatusEnum;

class CreateOrderListener
{
    public function handle(object $event): void
    {
        $inquiry = $event->inquiry;
        $order = Order::create([
            'company_id' => $inquiry->company_id,
            'customer_id' => $inquiry->customer_id,
            'date' => $inquiry->date,
            'description' => $inquiry->description,
            'fitting_charge' => $inquiry->fitting_charge,
            'pesting_charge' => $inquiry->pesting_charge,
            'transportation_charge' => $inquiry->transportation_charge,
            'total_amount' => $inquiry->total_amount,
            'discount' => $inquiry->discount,
            'status' => OrderStatusEnum::Confirmed->value,
            'estimated_amount' => $inquiry->estimated_amount,
            'rounded_amount' => $inquiry->rounded_amount,
        ]);
        // dd($inquiry->inquiryJobs);
        foreach ($inquiry->inquiryJobs as $inquiryJob) {
            $orderJob = $order->orderJobs()->create([
                'job_no' => $this->generateJobNumber(),
                'product_id' => $inquiryJob->product_id,
                'product_name' => $inquiryJob->product_name,
                'measurement_unit' => $inquiryJob->measurement_unit,
                'width' => $inquiryJob->width,
                'height' => $inquiryJob->height,
                'qty' => $inquiryJob->qty,
                'sq_ft' => $inquiryJob->sq_ft,
                'rate' => $inquiryJob->rate,
                'amount' => $inquiryJob->amount,
                'pesting_charge' => $inquiryJob->pesting_charge,
                'fitting_charge' => $inquiryJob->fitting_charge,
                // 'transportation_charge' => $inquiryJob->transportation_charge,
                'narration' => $inquiryJob->narration,
                'design_by' => $inquiry->created_by,
            ]);
            if ($inquiryJob->getFirstMedia('inquiry_job_image')) {
                $imagePath = $inquiryJob->getFirstMedia('inquiry_job_image')->getPath();
                $orderJob->addMedia($imagePath)->preservingOriginal()->toMediaCollection('order_job_image');
            }
        }
    }
    protected function generateJobNumber()
    {
        return getUniqueNo('OrderJob');
    }
}
