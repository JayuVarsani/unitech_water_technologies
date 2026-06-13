<div x-data='table'>
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6">
            @if($canView)
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="{{ __('app.panel.search_name', ['name' => 'Delivery']) }}">
                    </div>
                </div>
            @endif
            @if($canCreate)
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('company.delivery.create') }}" class="btn btn-primary btn-sm">
                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('app.panel.create_name', ['name' => 'Delivery']) }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        @if($canView)
            <div class="card-body pt-0">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <th class="text-start text-black">Date</th>
                        <th class="text-start text-black">Customer</th>
                        <th class="text-start text-black">Final Amount</th>
                        <th class="text-start text-black">Entry Type</th>
                        <th class="text-start text-black">Image</th>
                        @if($canView || $canEdit || $canDelete)
                            <th class="text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach($items as $item)
                        <tr wire:key="{{ $item->id }}">
                            <td class="text-start">{{ $item->delivery_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="text-start">{{ $item->customer_name ?? '-' }}</td>
                            <td class="text-start">₹ {{ number_format((float) $item->final_amount, 2) }}</td>
                            <td class="text-start">{{ $item->entry_type ?? '-' }}</td>
                            <td class="text-start">@if(!empty($item->getFirstMediaUrl('delivery_challan')))<img src="{{ $item->getFirstMediaUrl('delivery_challan') }}" style="height: 50px;width:50px;" alt="Delivery challan"/>@else {{ '-' }} @endif</td>
                            @if($canView || $canEdit || $canDelete)
                                <td>
                                    <x-panel::table.action.main>
                                        @if($canView)
                                            <x-panel::table.action.view :route="route('company.delivery.view', $item->id)" module="Delivery" />
                                        @endif
                                        @if($canEdit)
                                            <x-panel::table.action.edit :route="route('company.delivery.edit', $item->id)" module="Delivery" />
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
