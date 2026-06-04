<div x-data='filter'>
    <x-panel::alert />
    <x-panel::loader target="delete" />
    @if ($canView)
        <div class="card">
            <div class="card-header border-0 pt-6 justify-content-start gap-4">

                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="{{ __('app.panel.search_name', ['name' => __('company.order')]) }}">
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
                            @foreach (\App\Utility\Enums\OrderStatusEnum::cases() as $singlestatus)
                                <option value="{{ $singlestatus->value }}">{{ $singlestatus->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-toolbar">

                </div>
            </div>
            <div class="card-body pt-0" x-data="alert">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <!-- <th class="min-w-125px">{{ __('company.id') }}</th> -->
                        <th class="min-w-125px text-start text-black">{{ __('company.id') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.date') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.job_no') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.job_count') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.customer') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.total_amount') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.status') }}</th>
                        @if ($canEdit || $canDelete || $canView)
                            <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach ($items as $key => $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="text-start">{{ $item->id }}</td>
                                <td class="text-start">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}
                                </td>
                                <td class="text-start">
                                    {{ $item->orderJobs->pluck('job_no')->implode(', ') }}
                                </td>
                                <td class="text-start">
                                    {{ $item->orderJobs->count() }}
                                </td>
                                <td class="text-start">{{ $item->customer?->name }}</td>
                                <td class="text-start">{{ $item->estimated_amount }}</td>
                                <td class="text-start">
                                    @if ($item->status == \App\Utility\Enums\OrderStatusEnum::Cancelled->value)
                                        <span style="cursor: pointer" x-on:click="showCancellationReason(@js($item->cancellation_reason ?? ''))">{!! \App\Utility\Enums\OrderStatusEnum::tryFrom($item->status)?->getLabel() !!}</span>
                                    @else
                                        <span>{!! \App\Utility\Enums\OrderStatusEnum::tryFrom($item->status)?->getLabel() !!}</span>
                                    @endif
                                </td>
                                @if ($canEdit || $canDelete || $canView)
                                    <td>
                                        <x-panel::table.action.main>
                                            @if ($canEdit)
                                                @if ($item->status !== \App\Utility\Enums\OrderStatusEnum::Completed->value && $item->status !== \App\Utility\Enums\OrderStatusEnum::Cancelled->value)
                                                    <x-panel::table.action.edit :route="route('company.order.edit', $item->id)"
                                                        module="{{ __('company.order') }}" />
                                                @else
                                                    <a style="cursor: not-allowed" data-toggle="tooltip"
                                                        data-placement="top" title="Edit Order Details">
                                                        <i class="fa-solid fa-pencil icon text-secondary"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            @if ($canView)
                                                <x-panel::table.action.view :route="route('company.order.view', $item->id)"
                                                    module="{{ __('company.order') }}" />
                                            @endif
                                            {{-- @if ($canDelete)
                                                <a style="cursor: pointer"
                                                    wire:confirm="Are you sure you want delete this"
                                                    wire:click="delete({{ $item->id }})" data-toggle="tooltip"
                                                    data-placement="top" title="Delete Order Details">
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
        </div>
    @endif
</div>

@script
    <script>
        Alpine.data("filter", function() {
            return {
                startDate: "",
                endDate: "",
                status: "",
                initInputs() {
                    this.status = this.$wire.query.status;
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
                handleReset() {
                    this.$wire.query.status = "";
                    this.$wire.query.startDate = "";
                    this.$wire.query.endDate = "";
                    this.init();
                    this.$wire.$refresh();
                },
                handleFilter() {
                    this.$wire.query.status = this.status;
                    this.$wire.query.startDate = this.startDate;
                    this.$wire.query.endDate = this.endDate;
                    this.$wire.$refresh();
                }

            };
        });
    </script>
@endscript
