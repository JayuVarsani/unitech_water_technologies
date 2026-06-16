<div x-data='filter'>
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6 justify-content-start gap-4">
            @if($canView)
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="Search site, company, contact...">
                    </div>
                </div>
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-calendar fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" class="form-control form-control-solid w-250px ps-12" x-ref="timeRange"
                            placeholder="{{ __('app.panel.plc_select', ['name' => 'range']) }}"
                            x-bind:value="rangeValue" value="" />
                    </div>
                </div>
            @endif
        </div>
        @if($canView)
            <div class="card-body pt-0">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <th class="text-start text-black">Date</th>
                        <th class="text-start text-black">Site</th>
                        <th class="text-start text-black">Contact Person</th>
                        <th class="text-start text-black">Visit No.</th>
                        <th class="text-start text-black">Status</th>
                        @if($canView || $canEdit || $canDelete)
                            <th class="text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach($items as $key=>$item)
                        <tr wire:key="{{ $item->id }}">
                            <td class="text-start">{{ $item->visit_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="text-start">{{ $item->site_name ?? '-' }}</td>
                            <td class="text-start">{{ $item->contact_person ?? '-' }}</td>
                            <td class="text-start">{{ $item->visit_number ?? '-' }}</td>
                            <td class="text-start">
                                @if($item->status === 'completed')
                                    <span class="badge badge-light-success">Completed</span>
                                @else
                                    <span class="badge badge-light-warning">Pending</span>
                                @endif
                            </td>
                            @if($canView || $canEdit || $canDelete)
                                <td>
                                    <x-panel::table.action.main>
                                        @if($item->status === 'pending' && $canEdit)
                                            <a href="{{ route('company.visit.complete', $item->id) }}" class="btn btn-sm btn-primary">Complete Visit</a>
                                        @endif
                                        @if($item->status === 'completed' && $canView)
                                            <x-panel::table.action.view :route="route('company.visit.view', $item->id)" module="Visit" />
                                        @endif
                                        @if($item->status === 'completed' && $canEdit)
                                            <x-panel::table.action.edit :route="route('company.visit.edit',$item->id)" module="Visit" />
                                        @endif
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
            initInputs() {
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
                    startDate: th.startDate ? moment(th.startDate) : moment().startOf('month'),
                    endDate: th.endDate ? moment(th.endDate) : moment().endOf('month'),
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
                return (this.startDate && this.endDate)
                    ? moment(this.startDate).format("DD-MM-YYYY") + " - " + moment(this.endDate).format("DD-MM-YYYY")
                    : "";
            },
        };
    });
</script>
@endscript
