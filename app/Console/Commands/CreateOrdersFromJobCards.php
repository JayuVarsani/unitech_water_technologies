<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\JobCard;
use App\Models\Order;
use App\Utility\Enums\OrderJobStatusEnum;
use App\Utility\Enums\OrderStatusEnum;
use Illuminate\Console\Command;

class CreateOrdersFromJobCards extends Command
{
    protected $signature = 'company:create-orders-from-jobcards';

    protected $description = 'Create orders and order jobs from existing job_cards';

    public function handle(): int
    {
        JobCard::with(['wastages', 'media'])->where('status', 1)->get()->each(function ($jobCard) {
            $status = $jobCard->job_status;

            $status_to_set = $status;
            $job_status_to_set = $status;
            if ($status == 'pending' || $status == 'design') {
                $status_to_set = OrderStatusEnum::Confirmed->value;
            } elseif ($status == 'completed') {
                $status_to_set = OrderStatusEnum::Completed->value;
            } elseif ($status == 'cancelled') {
                $status_to_set = OrderStatusEnum::Cancelled->value;
            } elseif ($status == 'printed') {
                $status_to_set = OrderStatusEnum::Printed->value;
            } elseif ($status == 'pesting') {
                $status_to_set = OrderStatusEnum::Completed->value;
            } elseif ($status == 'fitting') {
                $status_to_set = OrderStatusEnum::Completed->value;
            }
            $order = Order::create([
                'id' => $jobCard->id,
                'company_id' => $jobCard->company_id,
                'customer_id' => $jobCard->customer_id,
                'date' => $jobCard->job_date,
                'description' => $jobCard->description,
                'fitting_charge' => $jobCard->fitting_charge,
                'pesting_charge' => $jobCard->pesting_charge,
                'transportation_charge' => $jobCard->transportation_charge,
                'total_amount' => $jobCard->amount,
                'status' => $status_to_set,
                'estimated_amount' => $jobCard->amount,
                'created_at' => $jobCard->created_at,
                'updated_at' => $jobCard->updated_at,
            ]);
            // dd($order);
            if ($status == 'design') {
                $job_status_to_set = OrderJobStatusEnum::Design->value;
            }
            elseif ($status == 'printed') {
                $job_status_to_set = OrderJobStatusEnum::Printed->value;
            }
            elseif ($status == 'pesting' || $status == 'fitting' || $status == 'completed') {
                $job_status_to_set = OrderJobStatusEnum::Completed->value;
            }
            elseif ($status == 'pending') {
                $job_status_to_set = OrderJobStatusEnum::Design->value;
            }
            elseif ($status == 'cancelled') {
                $job_status_to_set = OrderJobStatusEnum::Cancelled->value;
            }
            $orderJob = $order->orderJobs()->create([
                'id' => $jobCard->id,
                'job_no' => $jobCard->job_no,
                'product_id' => $jobCard->product_id,
                'product_name' => $jobCard->product_name,
                'measurement_unit' => $jobCard->is_inch == 1 ? 'inch' : 'feet',
                'print_by' => $jobCard->staff_id,
                'width' => $jobCard->width,
                'height' => $jobCard->height,
                'qty' => $jobCard->qty,
                'sq_ft' => $jobCard->sq_ft,
                'rate' => $jobCard->rate,
                'amount' => $jobCard->amount,
                'status' => $job_status_to_set,
                'estimated_amount' => $jobCard->amount + $jobCard->fitting_charge + $jobCard->pesting_charge + $jobCard->transportation_charge,
                'created_at' => $jobCard->created_at,
                'updated_at' => $jobCard->updated_at,
            ]);
            $media = $jobCard->getFirstMedia('job_image');
            if ($media) {
                $imagePath = $media->getAvailablePath(['compressed']);
                if (is_readable($imagePath)) {
                    $orderJob->addMedia($imagePath)->preservingOriginal()->toMediaCollection('order_job_image');
                }
            }
            foreach($jobCard->wastages as $wastage){
                $materialId = (int) ($wastage->material_id ?? 0);
                $orderJob->wastages()->create([
                    'id' => $wastage->id,
                    'damage_type' => $wastage->damage_type,
                    'note' => $wastage->note,
                    'material_id' => $materialId > 0 ? $materialId : null,
                    'width' => $wastage->width,
                    'height' => $wastage->height,
                ]);
            }
        });

        return self::SUCCESS;
    }
}
