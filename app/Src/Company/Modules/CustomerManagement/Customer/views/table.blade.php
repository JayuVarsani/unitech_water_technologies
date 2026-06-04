<div x-data="table">
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6 d-flex flex-wrap align-items-center gap-3 gap-lg-4">
            @if ($canView)
                <div class="card-title flex-grow-1 mb-0">
                    <div class="input-group input-group-solid w-250px mw-100">
                        <span class="input-group-text border-0 px-4">
                            <i class="ki-duotone ki-magnifier fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </span>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid border-0"
                            placeholder="{{ __('app.panel.search_name', ['name' => __('company.customer-management.customer')]) }}">
                    </div>
                </div>
            @endif
            <div class="card-toolbar ms-auto d-flex flex-wrap align-items-center gap-2 gap-lg-3 mb-0">
                @if ($canView)
                    <div class="d-flex justify-content-start align-items-start ms-0" x-data="table">
                        <button class="btn btn-sm btn-flex btn-primary fw-bold btn-sm" x-ref="filterDiv"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="fa-solid fa-filter"></i>
                            Filter
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-500px w-md-500px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5 row">
                                <div class="mb-10 col-md-12">
                                    <label class="form-label fw-semibold">Customer Group:</label>
                                    <div>
                                        <select class="form-select form-select-solid" x-model="customerGroupId">
                                            <option value="">Select Customer Group</option>
                                            @foreach ($customerGroups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
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
                @endif
                @if ($canCreate)
                    <a href="{{ route('company.customer-management.customer.create') }}" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        {{ __('app.panel.create_name', ['name' => __('company.customer-management.customer')]) }}
                    </a>
                @endif
            </div>
        </div>
        @if ($canView)
            <div class="card-body pt-0">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.name') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.contact_number') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.customer_group') }}</th>
                        @if ($canEdit || $canDelete)
                            <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach ($items as $key => $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="text-start">{{ $item->name }}</td>
                                <td class="text-start">{{ $item->contact_number }}</td>
                                <td class="text-start">{{ $item->customer_group_name }}</td>
                                @if ($canEdit || $canDelete)
                                    <td>
                                        <x-panel::table.action.main>
                                            @if ($canView)
                                                <x-panel::table.action.view :route="route(
                                                    'company.customer-management.customer.view',
                                                    $item->id,
                                                )"
                                                    module="{{ __('company.customer-management.customer') }}" />
                                            @endif
                                            @if ($canEdit)
                                                <x-panel::table.action.edit :route="route(
                                                    'company.customer-management.customer.edit',
                                                    $item->id,
                                                )"
                                                    module="{{ __('company.customer-management.customer') }}" />
                                            @endif
                                            @if ($canDelete)
                                                <a x-on:click="showAlert({{ $item->id }})"><i
                                                        class="fa-solid fa-trash icon text-danger"></i></a>
                                            @endif
                                            @if ($canViewOrder)
                                                <x-panel::table.action.order :route="route('company.order.index', ['search' => $item->name])"
                                                    module="{{ __('company.order') }}" />
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
        Alpine.data('table', function() {
            return {
                customerGroupId: "",
                initInputs() {
                    this.customerGroupId = this.$wire.query.customerGroupId;
                },

                init() {
                    this.initInputs();
                },
                handleReset() {
                    this.$wire.query.customerGroupId = "";
                    this.init();
                    this.$wire.$refresh();
                },
                handleFilter() {
                    this.$wire.query.customerGroupId = this.customerGroupId;
                    this.$wire.$refresh();
                },
                showAlert(id) {
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
