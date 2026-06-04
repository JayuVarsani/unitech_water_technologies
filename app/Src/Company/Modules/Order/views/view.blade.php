<div x-data="order">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <div class="card-body border-top px-6 py-2">
            <div class="px-4 py-4">
                <div class="row g-4">
                    <div class="col-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="symbol symbol-35px symbol-circle bg-light">
                                <span class="symbol-label bg-light">
                                    <i class="fa-regular fa-hashtag text-gray-500 fs-6"></i>
                                </span>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark fs-7">{{ __('company.order_id') }}</span>
                                <span class="fw-bold text-gray-900 fs-6">{{ $order->id }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="symbol symbol-35px symbol-circle bg-light">
                                <span class="symbol-label bg-light">
                                    <i class="fa-regular fa-calendar text-gray-500 fs-6"></i>
                                </span>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark fs-7">{{ __('company.input.date') }}</span>
                                <span
                                    class="fw-bold text-gray-900 fs-6">{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="symbol symbol-35px symbol-circle bg-light">
                                <span class="symbol-label bg-light">
                                    <i class="fa-regular fa-user text-gray-500 fs-6"></i>
                                </span>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark fs-7">{{ __('company.input.customer') }}</span>
                                <span class="fw-bold text-gray-900 fs-6">{{ $order->customer->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="symbol symbol-35px symbol-circle bg-light">
                                <span class="symbol-label bg-light">
                                    <i class="fa-regular fa-circle-check text-gray-500 fs-6"></i>
                                </span>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark fs-7">{{ __('company.input.status') }}</span>
                                <span class="fw-bold text-gray-900 fs-6">{!! \App\Utility\Enums\OrderStatusEnum::tryFrom($order->status)?->getLabel() !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="text-muted">
            </br>
            <div class="col-md-12">
                <div class="card-body p-4" x-data="alert">
                    <x-panel::table.main :items="$order->orderJobs" :pagination="false">
                        <x-panel::table.head>
                            <th class="text-black">{{ __('company.input.jobno') }}</th>
                            <th class="text-black">{{ __('company.input.image') }}</th>
                            <th class="text-black">{{ __('company.input.product') }}</th>
                            <th class="text-black">{{ __('company.input.status') }}</th>
                            <th class="text-black">{{ __('company.input.unit') }}</th>
                            <th class="text-black">{{ __('company.input.width') }}</th>
                            <th class="text-black">{{ __('company.input.height') }}</th>
                            <th class="text-black">{{ __('company.input.qty') }}</th>
                            <th class="text-black">{{ __('company.input.sq_ft') }}</th>
                            <th class="text-black">{{ __('company.input.rate') }}</th>
                            <th class="text-black">{{ __('company.input.pesting_charge') }}</th>
                            <th class="text-black">{{ __('company.input.fitting_charge') }}</th>
                            <th class="text-black">{{ __('company.input.amount') }}</th>
                            <th class="text-black">{{ __('company.action') }}</th>
                        </x-panel::table.head>
                        <x-panel::table.body :items="$order->orderJobs">
                            @foreach ($order->orderJobs as $job)
                                <tr wire:key="job-{{ $job->id }}">
                                    <td>
                                        {{ $job->job_no ?? '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ $job->image_url }}" target="_blank" data-fancybox>
                                            <img src="{{ $job->image_url }}" style="height: 25px;width:25px;"
                                                alt="Job Image" />
                                        </a>
                                    </td>
                                    <td>{{ $job->product_name ?? '-' }}</td>
                                    <td>
                                        @if ($job->status == \App\Utility\Enums\OrderJobStatusEnum::Cancelled->value)
                                            <span style="cursor: pointer" x-on:click="showCancellationReason(@js($job->cancellation_reason ?? ''))">{!! \App\Utility\Enums\OrderJobStatusEnum::tryFrom($job->status)?->getLabel() !!}</span>
                                        @else
                                            <span>{!! \App\Utility\Enums\OrderJobStatusEnum::tryFrom($job->status)?->getLabel() !!}</span>
                                        @endif
                                    </td>
                                    <td>{{ $job->measurement_unit ?? '-' }}</td>
                                    <td>{{ $job->width ?? '-' }}</td>
                                    <td>{{ $job->height ?? '-' }}</td>
                                    <td>{{ $job->qty ?? '-' }}</td>
                                    <td>{{ $job->sq_ft ?? '-' }}</td>
                                    <td>{{ $job->rate ?? '-' }}</td>
                                    <td>{{ $job->pesting_charge ?? '-' }}</td>
                                    <td>{{ $job->fitting_charge ?? '-' }}</td>
                                    <td>{{ $job->amount ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center action-div">
                                            <span title="{{ $job->narration }}">
                                                @if ($job->narration)
                                                    <span style="cursor: pointer" x-on:click="showNarration('{{ $job->narration }}')"><i class="fa-solid fa-info-circle text-dark"></i></span>
                                                @else
                                                    <span style="cursor: not-allowed"><i class="fa-solid fa-circle-info text-muted"></i></span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="10"></td>
                                <td class="text-center"><span
                                        class="fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.total') }}</span>
                                    {{ $order->orderJobs->sum('pesting_charge') ?? '-' }}</td>
                                <td class="text-center"><span
                                        class="fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.total') }}</span>
                                    {{ $order->orderJobs->sum('fitting_charge') ?? '-' }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </x-panel::table.body>
                        <tr>
                            <td colspan="10" class="align-top">
                                <div class="mb-4 border border-gray-300 rounded px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-35px symbol-circle bg-light">
                                            <span class="symbol-label bg-light">
                                                <i class="fa-regular fa-note-sticky text-gray-500 fs-6"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="fw-semibold text-dark fs-7 text-start">{{ __('company.input.narration') }}</span>
                                            <span
                                                class="text-gray-900 text-start">{{ $order->description ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($order->status == \App\Utility\Enums\OrderStatusEnum::Cancelled->value)
                                    <div class="mb-4 border border-gray-300 rounded px-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="symbol symbol-35px symbol-circle bg-light-danger">
                                                <span class="symbol-label bg-light-danger">
                                                    <i class="fa-regular fa-circle-xmark text-danger fs-6"></i>
                                                </span>
                                            </span>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-semibold text-danger fs-7 text-start">{{ __('company.input.cancellation_reason') }}</span>
                                                <span
                                                    class="text-danger text-start">{{ $order->cancellation_reason ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td colspan="6">
                                <div class="card border-0 p-0 h-100">
                                    <div class="card-body p-3 ">
                                        <div class="d-flex flex-column gap-3">
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom border-gray-300 pb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i
                                                                class="fa-regular fa-money-bill-1 text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.total_amount') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->total_amount ?? 0 }}</span>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom border-gray-300 pb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i class="fa-regular fa-square-plus text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.pesting_charge') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->pesting_charge ?? 0 }}</span>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom border-gray-300 pb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i class="fa-regular fa-square-plus text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.fitting_charge') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->fitting_charge ?? 0 }}</span>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom border-gray-300 pb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i class="fa-regular fa-square-plus text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.transportation_charge') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->transportation_charge ?? 0 }}</span>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom border-gray-300 pb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i
                                                                class="fa-regular fa-square-minus text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.discount') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->discount ?? 0 }}</span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between border-bottom border-gray-300 pb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i class="fa-regular fa-circle-check text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.rounded_amount') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->rounded_amount ?? 0 }}</span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between pt-1">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="symbol symbol-35px symbol-circle bg-light">
                                                        <span class="symbol-label bg-light">
                                                            <i class="fa-regular fa-circle-dot text-gray-500 fs-6"></i>
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="fw-semibold text-black fs-7">{{ __('company.input.estimated_amount') }}</span>
                                                </div>
                                                <span
                                                    class="fw-bold text-gray-900 fs-6">{{ $order->estimated_amount ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </x-panel::table.main>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('company.order.index') }}" class="btn btn-light btn-active-light-primary me-2"
                id="cancelButton">{{ __('app.panel.back') }}</a>
            @if ($canEdit && $order->status !== \App\Utility\Enums\OrderStatusEnum::Completed->value && $order->status !== \App\Utility\Enums\OrderStatusEnum::Cancelled->value)
                <a href="{{ route('company.order.edit', $order->id) }}" class="btn btn-primary">
                    {{ __('app.panel.edit') }}
                    {{ __('company.order') }}
                </a>
            @endif
        </div>
    </div>
</div>
@script
    <script>
        Fancybox.bind("[data-fancybox]", {
            hideScrollbar: false
        });
    </script>
@endscript