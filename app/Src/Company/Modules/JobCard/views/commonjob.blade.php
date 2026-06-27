<div x-data='filter'>
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6">



            @if ($canView)

                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="{{ __('app.panel.search_name', ['name' => __('company.jobcard')]) }}">
                    </div>
                </div>

                <div class="card-toolbar">

                    <div class="d-flex justify-content-start align-items-start ms-0" x-data="filter">
                        <button class="btn btn-sm btn-flex btn-primary fw-bold btn-sm" x-ref="filterDiv"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="fa-solid fa-filter"></i>
                            Filter
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-1000px w-md-700px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5 row">


                                <div class="mb-10 col-md-6">
                                    <label class="form-label fw-semibold">Status:</label>
                                    <div>
                                        <select class="form-select form-select-solid" x-model="job_status">
                                            <option value="">Select Status</option>
                                            <option value="completed">Completed</option>
                                            <option value="pending">Pending</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-10 col-md-6">
                                    <label class="form-label fw-semibold">Company:</label>
                                    <div>
                                        <select class="form-select form-select-solid" x-model="customer_id_filter">
                                            <option value="">Select Company</option>
                                            @foreach ($customer as $singlecustomer)
                                                <option value="{{ $singlecustomer->id }}">{{ $singlecustomer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-10 col-md-6">
                                    <label class="form-label fw-semibold">Range :</label>
                                    <div>
                                        <input type="text" class="form-control" x-ref="timeRange"
                                            placeholder="{{ __('app.panel.plc_select', ['name' => 'range']) }}"
                                            x-bind:value="rangeValue" value="" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" x-on:click="handleReset"
                                            class="btn btn-sm btn-light btn-active-light-primary me-2"
                                            data-kt-menu-dismiss="true">
                                            Reset
                                        </button>
                                        <button type="button" x-on:click="handleFilter" class="btn btn-sm btn-primary"
                                            data-kt-menu-dismiss="true">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            @if ($canCreate)
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('company.jobcard.create') }}" class="btn btn-primary btn-sm">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            {{ __('app.panel.create_name', ['name' => __('company.jobcard')]) }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        @if ($canView)
            <div class="card-body pt-0">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <!-- <th class="min-w-125px">{{ __('company.id') }}</th> -->
                        <th class="min-w-125px text-start text-black">{{ __('company.input.jobno') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.date') }}</th>

                        <th class="min-w-125px text-start text-black">{{ __('company.input.customer') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.jobname') }}</th>

                        <th class="min-w-125px text-start text-black">{{ __('company.input.image') }}</th>

                        <th class="min-w-125px text-start text-black">{{ __('company.input.status') }}</th>
                        @if ($canEdit || $canDelete)
                            <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach ($items as $key => $item)
                            <tr wire:key="{{ $item->id }}" @class([
                                'bg-light-success' => $item->job_status === \App\Utility\Enums\JobCardStatusTypeEnum::Completed->value,
                                'bg-light-warning' => $item->job_status === \App\Utility\Enums\JobCardStatusTypeEnum::Printed->value,
                            ])>
                                <!-- <td>{{ $item->id }}</td> -->
                                <td class="text-start" style="cursor: pointer;">
                                    <span class="job-no-span" onclick="copyToClipboard(this)">{{ $item->job_no }}</span>
                                </td>
                                <td class="text-start">{{ \Carbon\Carbon::parse($item->job_date)->format('d-m-Y') }}
                                </td>
                                <td class="text-start">{{ $item->customer->name }}</td>
                                <td class="text-start">{{ $item->product_name }}</td>
                                <td class="text-start">
                                    @if (!empty($item->getfirstMediaUrl('job_image', 'compressed')))
                                        <a href="{{ $item->getFirstMediaUrl('job_image', 'compressed') }}"
                                            target="_blank">
                                            <img src="{{ $item->getfirstMediaUrl('job_image', 'compressed') }}"
                                                style="height: 50px;width:50px;" alt="Jobcard Image" /></a>
                                    @else
                                        {{ '-' }}
                                    @endif
                                </td>
                                <td class="text-start">{{ ucfirst($item->job_status) }}</td>
                                @if ($canEdit || $canDelete)
                                    <td>
                                        <x-panel::table.action.main>
                                            @if ($canEdit)
                                                <x-panel::table.action.edit :route="route('company.jobcard.edit', $item->id)"
                                                    module="{{ __('company.jobcard') }}" />
                                            @endif

                                            @if ($canDelete && $item->job_status !== 'completed')
                                                {{-- <a href="javascript:void(0);" 
                                            
                                            x-on:click="showAlert({{ $item->id }})">
                                                <i class="fa-solid fa-trash icon text-danger"></i>
                                            </a> --}}
                                                @if (
                                                    $item->job_status == \App\Utility\Enums\JobCardStatusTypeEnum::Pending->value ||
                                                        $item->job_status == \App\Utility\Enums\JobCardStatusTypeEnum::Design->value)
                                                    <a style="cursor: pointer"
                                                        wire:confirm="Are you sure you want delete this"
                                                        wire:click="delete({{ $item->id }})" data-toggle="tooltip"
                                                        data-placement="top" title="Delete Fee Details">
                                                        <i class="fa-solid fa-trash icon text-danger"></i>
                                                    </a>
                                                @else
                                                    <a style="cursor: not-allowed" data-toggle="tooltip"
                                                        data-placement="top" title="Delete Fee Details">
                                                        <i class="fa-solid fa-trash icon text-secondary"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            {{-- @if ($canView)
                                        <x-panel::table.action.view :route="route('company.jobcard.edit',$item->id)" module="{{__('company.jobcard')}}" />
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
        Alpine.data("filter", function() {
            return {
                startDate: "",
                endDate: "",
                job_status: "",
                customer_id_filter: "",
                initInputs() {
                    this.job_status = this.$wire.query.job_status;
                    this.customer_id_filter = this.$wire.query.customer_id_filter;
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
                        opens: "left",
                        startDate: th.startDate ? moment(th.startDate) : moment(new Date()),
                        endDate: th.endDate ? moment(th.endDate) : moment(new Date()),
                        locale: {
                            format: "DD-MM-YYYY"
                        }
                    }).on("cancel.daterangepicker", function() {
                        th.dateRangeStatic();
                    }).on("apply.daterangepicker", function(ev, picker) {
                        th.startDate = picker.startDate.format("YYYY-MM-DD");
                        th.endDate = picker.endDate.format("YYYY-MM-DD");
                        th.dateRangeStatic();
                    });
                },
                dateRangeStatic() {
                    const filterDiv = this.$refs.filterDiv;
                    filterDiv.dataset["ktMenuStatic"] = true;
                    setTimeout(() => filterDiv.dataset["ktMenuStatic"] = false, 500);
                },
                rangeValue() {
                    return (this.startDate && this.endDate) ? moment(this.startDate).format("DD-MM-YYYY") + " - " +
                        moment(this.endDate).format("DD-MM-YYYY") : "";
                },
                handleReset() {
                    this.$wire.query.job_status = "";
                    this.$wire.query.customer_id_filter = "";
                    this.$wire.query.startDate = "";
                    this.$wire.query.endDate = "";
                    this.init();
                    this.$wire.$refresh();
                },
                handleFilter() {
                    this.$wire.query.job_status = this.job_status;
                    this.$wire.query.customer_id_filter = this.customer_id_filter;
                    this.$wire.query.startDate = this.startDate;
                    this.$wire.query.endDate = this.endDate;
                    this.$wire.$refresh();
                },
                showAlert(id) {
                    // Show a confirmation alert before deleting
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.delete(id);
                        }
                    });
                }

            };



        });
    </script>
@endscript
