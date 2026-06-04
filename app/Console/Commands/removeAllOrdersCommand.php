<?php

namespace App\Console\Commands;

use App\Models\Inquiry;
use App\Models\Order;
use Illuminate\Console\Command;

class removeAllOrdersCommand extends Command
{
    protected $signature = 'app:remove-all-orders';

    protected $description = 'Remove all orders/inquiries with job media';

    public function handle(): int
    {
        $this->warn('This will delete all orders/inquiries, jobs and attached images.');
        if (! $this->confirm('Do you want to continue?', false)) {
            $this->info('Aborted.');
            return self::SUCCESS;
        }

        $deletedOrders = 0;
        $deletedOrderJobs = 0;
        $deletedOrderMedia = 0;
        $deletedInquiries = 0;
        $deletedInquiryJobs = 0;
        $deletedInquiryMedia = 0;

        Order::query()
            ->with('orderJobs')
            ->orderBy('id')
            ->chunkById(100, function ($orders) use (&$deletedOrders, &$deletedOrderJobs, &$deletedOrderMedia): void {
                foreach ($orders as $order) {
                    foreach ($order->orderJobs as $orderJob) {
                        $deletedOrderMedia += $orderJob->media()->count();
                        $orderJob->clearMediaCollection('order_job_image');
                        $orderJob->delete();
                        $deletedOrderJobs++;
                    }

                    $order->delete();
                    $deletedOrders++;
                }
            });

        Inquiry::query()
            ->with('inquiryJobs')
            ->orderBy('id')
            ->chunkById(100, function ($inquiries) use (&$deletedInquiries, &$deletedInquiryJobs, &$deletedInquiryMedia): void {
                foreach ($inquiries as $inquiry) {
                    foreach ($inquiry->inquiryJobs as $inquiryJob) {
                        $deletedInquiryMedia += $inquiryJob->media()->count();
                        $inquiryJob->clearMediaCollection('inquiry_job_image');
                        $inquiryJob->delete();
                        $deletedInquiryJobs++;
                    }

                    $inquiry->delete();
                    $deletedInquiries++;
                }
            });

        $this->info("Deleted orders: {$deletedOrders}");
        $this->info("Deleted order jobs: {$deletedOrderJobs}");
        $this->info("Deleted order media files: {$deletedOrderMedia}");
        $this->info("Deleted inquiries: {$deletedInquiries}");
        $this->info("Deleted inquiry jobs: {$deletedInquiryJobs}");
        $this->info("Deleted inquiry media files: {$deletedInquiryMedia}");

        return self::SUCCESS;
    }
}
