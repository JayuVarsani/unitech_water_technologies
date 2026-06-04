<div x-data='filter'>
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6 justify-content-start gap-4">
            @if ($canView)
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="{{ __('app.panel.search_name', ['name' => __('company.orderjob')]) }}">
                    </div>
                </div>
                <div class="card-title" x-data="filter">
                    <div class="">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-calendar fs-3 position-absolute ms-5"><span class="path1"></span>
                                <span class="path2"></span></i>
                            <input type="text" class="form-control w-250px ps-12" x-ref="timeRange"
                                placeholder="{{ __('app.panel.plc_select', ['name' => 'range']) }}"
                                x-bind:value="rangeValue" value="" />
                        </div>
                    </div>
                </div>
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-filter fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <select class="form-select form-select-solid w-250px ps-12" wire:model.live="query.status">
                            <option value="">{{ __('app.panel.plc_select', ['name' => 'status']) }}</option>
                            @foreach (\App\Utility\Enums\OrderJobStatusEnum::cases() as $singlestatus)
                                <option value="{{ $singlestatus->value }}">{{ $singlestatus->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- <div class="card-title">
                    <button type="button" x-on:click="handleReset" class="btn btn-sm btn-light btn-light-danger">
                        <span class="text-reset"><i class="fa-solid fa-xmark"></i> Reset</span>
                    </button>
                </div> --}}
            @endif
        </div>
        @if ($canView)
            <div class="card-body pt-0" x-data="alert">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <!-- <th class="min-w-125px">{{ __('company.id') }}</th> -->
                        <th class="min-w-100px text-start text-black">{{ __('company.input.jobno') }}</th>
                        <th class="min-w-100px text-start text-black">{{ __('company.input.orderno') }}</th>

                        <th class="min-w-125px text-start text-black">{{ __('company.input.date') }}</th>

                        <th class="min-w-125px text-start text-black">{{ __('company.input.customer') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.jobname') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.design_by') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.print_by') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.image') }}</th>

                        <th class="min-w-125px text-start text-black">{{ __('company.input.status') }}</th>
                        @if ($canEdit)
                            <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach ($items as $key => $item)
                            <!-- <td>{{ $item->id }}</td> -->
                            <tr wire:key="{{ $item->id }}" @class([
                                'bg-light-success' =>
                                    $item->status ===
                                    \App\Utility\Enums\OrderJobStatusEnum::Completed->value,
                                'bg-light-warning' =>
                                    $item->status === \App\Utility\Enums\OrderJobStatusEnum::Printed->value,
                                'bg-light-danger' =>
                                    $item->status ===
                                    \App\Utility\Enums\OrderJobStatusEnum::Cancelled->value,
                                'bg-light-info' =>
                                    $item->status === \App\Utility\Enums\OrderJobStatusEnum::Design->value,
                            ])>
                                <td class="text-start" style="cursor: pointer;">
                                    <span class="job-no-span"
                                        onclick="copyToClipboard(this)">{{ $item->job_no }}</span>
                                </td>
                                <td class="text-start">{{ $item->order->id }}</td>
                                <td class="text-start">{{ \Carbon\Carbon::parse($item->order->date)->format('d-m-Y') }}
                                </td>
                                <td class="text-start">{{ $item->order->customer->name }}</td>
                                <td class="text-start">{{ $item->product_name }}</td>
                                <td class="text-start">{{ $item->designBy ? $item->designBy->name : '-' }}</td>
                                <td class="text-start">{{ $item->printBy ? $item->printBy->name : '-' }}</td>
                                <td class="text-start">
                                    @if (!empty($item->getfirstMediaUrl('order_job_image')))
                                        <a href="{{ $item->getFirstMediaUrl('order_job_image') }}" target="_blank" data-fancybox
                                            style="width: 50px; height: 50px;"
                                            class="d-inline-block align-middle overflow-hidden rounded">
                                            <img src="{{ $item->getfirstMediaUrl('order_job_image') }}"
                                                class="d-block w-100 h-100" alt="Order Job Image"
                                                style="object-fit: cover; object-position: center;"
                                                alt="Order Job Image" /></a>
                                    @else
                                        {{ '-' }}
                                    @endif
                                </td>
                                <td class="text-start">
                                    @if ($item->status == \App\Utility\Enums\OrderJobStatusEnum::Cancelled->value)
                                        <span class="cursor-pointer"
                                            x-on:click="showCancellationReason(@js($item->cancellation_reason ?? ''))">{!! \App\Utility\Enums\OrderJobStatusEnum::tryFrom($item->status)->getLabel() !!}</span>
                                    @elseif ($item->status == \App\Utility\Enums\OrderJobStatusEnum::Completed->value)
                                        <span>{!! \App\Utility\Enums\OrderJobStatusEnum::tryFrom($item->status)->getLabel() !!}</span>
                                    @else
                                        <select class="form-select form-select-solid"
                                            wire:key="order-job-status-{{ $item->id }}" {{-- wire:change="changeStatus({{ $item->id }}, $event.target.value)" --}}
                                            {{-- x-on:change="if (confirm('Are you sure you want to change the status to ' + $event.target.value + '?')) {
                                                $wire.changeStatus({{ $item->id }}, $event.target.value)
                                                } else {
                                                    $event.target.value = '{{ $item->status }}'
                                                    }" --}}
                                            x-on:change="showConfirmationAlert({{ $item->id }}, $event, '{{ $item->status }}')">
                                            @foreach (\App\Utility\Enums\OrderJobStatusEnum::cases() as $singlestatus)
                                                <option {{ $item->status === $singlestatus->value ? 'selected' : '' }}
                                                    value="{{ $singlestatus->value }}">{{ $singlestatus->getName() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                @if ($canEdit)
                                    <td>
                                        <x-panel::table.action.main>
                                            @if ($canEdit)
                                                @if (
                                                    $item->status !== \App\Utility\Enums\OrderStatusEnum::Completed->value &&
                                                        $item->status !== \App\Utility\Enums\OrderStatusEnum::Cancelled->value)
                                                    <x-panel::table.action.edit :route="route('company.order.edit', [
                                                        'order' => $item->order->id,
                                                        'orderJob' => $item->id,
                                                    ])"
                                                        module="{{ __('company.order') }}" />
                                                @else
                                                    <a style="cursor: not-allowed" data-toggle="tooltip"
                                                        data-placement="top" title="Edit Order Details">
                                                        <i class="fa-solid fa-pencil icon"></i>
                                                    </a>
                                                @endif
                                            @endif

                                            {{-- @if ($canDelete && $item->status == \App\Utility\Enums\OrderJobStatusEnum::Design->value)
                                                <a style="cursor: pointer"
                                                    wire:confirm="Are you sure you want delete this"
                                                    wire:click="delete({{ $item->id }})" data-toggle="tooltip"
                                                    data-placement="top" title="Delete Order Job Details">
                                                    <i class="fa-solid fa-trash icon text-danger"></i>
                                                </a>
                                            @endif --}}
                                        </x-panel::table.action.main>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </x-panel::table.body>
                </x-panel::table.main>
            </div>
        @endif
    </div>
</div>

@script
    <script>
        Fancybox.bind("[data-fancybox]", {
            hideScrollbar: false
        });
        Alpine.data("filter", function() {
            return {
                startDate: "",
                endDate: "",
                job_status: "",
                initInputs() {
                    this.job_status = this.$wire.query.job_status;
                    this.startDate = this.$wire.query.startDate;
                    this.endDate = this.$wire.query.endDate;
                },

                init() {
                    this.initInputs();
                    this.initDateRangePicker();
                },
                initDateRangePicker() {
                    const th = this;
                    $(this.$refs.timeRange).daterangepicker({
                        autoUpdateInput: false,
                        opens: "left",
                        startDate: th.startDate ? moment(th.startDate) : moment(new Date()),
                        endDate: th.endDate ? moment(th.endDate) : moment(new Date()),
                        locale: {
                            format: "DD-MM-YYYY"
                        }
                    }).on("cancel.daterangepicker", function() {
                        th.cancelDateRange();
                    }).on("apply.daterangepicker", function(ev, picker) {
                        th.startDate = picker.startDate.format("YYYY-MM-DD");
                        th.endDate = picker.endDate.format("YYYY-MM-DD");
                        th.setDateRange();
                    });
                },
                setDateRange() {
                    this.$wire.query.startDate = this.startDate;
                    this.$wire.query.endDate = this.endDate;
                    this.init();
                    this.$wire.$refresh();
                },
                cancelDateRange() {
                    this.$wire.query.startDate = "";
                    this.$wire.query.endDate = "";
                    this.init();
                    this.$wire.$refresh();
                },
                rangeValue() {
                    return (this.startDate && this.endDate) ? moment(this.startDate).format("DD-MM-YYYY") + " - " +
                        moment(this.endDate).format("DD-MM-YYYY") : "";
                },
                // handleReset() {
                //     this.$wire.query.job_status = "";
                //     this.$wire.query.startDate = "";
                //     this.$wire.query.endDate = "";
                //     this.init();
                //     this.$wire.$refresh();
                // },
            };
        });
    </script>
@endscript
