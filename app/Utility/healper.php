<?php

declare(strict_types=1);

use App\Models\Inquiry;
use App\Models\InquiryJob;
use App\Models\OrderJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

function stripEmptyValueFromArray(array $data): array
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = stripEmptyValueFromArray($value);
        }
        if (empty($value)) {
            unset($data[$key]);
        }
    }

    return $data;
}

function flashAlert(string $message, string $type = 'success' | 'danger' | 'warning'): void
{
    if (! in_array($type, ['danger', 'success', 'warning'])) {
        throw new Exception('Invalid Alert Type Provided.');
    }
    session()->flash('alert', array_merge(session()->get('alert', []), [
        ['type' => $type, 'message' => $message],
    ]));
}

function cacheCallBack($key, callable $callback)
{
    $time = app()->isLocal() ? now()->addSeconds(5) : now()->addMinutes(10);

    return Cache::remember($key, $time, $callback);
}

function naChecker($val): string
{
    return $val ?? 'N/A';
}

function utcToLocal($time, $format = 'Y-m-d H:i:s'): string
{
    return Carbon::parse($time, 'UTC')->setTimezone('Asia/Calcutta')->format($format);
}
function getCacheTime(): Carbon
{
    return app()->isLocal() ? now()->addSecond() : now()->addHour();
}

function getUniqueNo($className): string
{
    $lastJob = null;
    $prefix = null;
    switch ($className) {
        case 'InquiryJob':
            $lastJob = InquiryJob::all()
                ->pluck('job_no')
                ->map(function ($job) {
                    return str_replace('IJ', '', $job);
                })
                ->max() ?? 0;
            $prefix = 'IJ';
            break;
        case 'OrderJob':
            $lastJob = OrderJob::all()
                ->pluck('job_no')
                ->map(function ($job) {
                    return str_replace(['OJ', 'A'], '', $job);
                })
                ->max() ?? 0;
            $prefix = 'A';
            break;
        default:
            throw new Exception('Invalid Class Name Provided.');
    }
    return $prefix . ($lastJob + 1);
}
function roundUp($amount, $nearest = 10)
{
    return ceil((float) $amount / $nearest) * $nearest;
}   