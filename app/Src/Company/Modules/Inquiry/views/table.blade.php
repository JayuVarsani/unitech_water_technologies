<div x-data='alert'>
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
                            placeholder="{{ __('app.panel.search_name', ['name' => __('company.inquiry')]) }}">
                    </div>
                </div>

                <div class="card-toolbar">
                </div>
            @endif

            @if ($canCreate)
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('company.inquiry.create') }}" class="btn btn-primary btn-sm">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            {{ __('app.panel.create_name', ['name' => __('company.inquiry')]) }}
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
                        <th class="min-w-125px text-start text-black">{{ __('company.id') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.date') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.customer') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.total_amount') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.status') }}</th>
                        @if ($canEdit || $canDelete)
                            <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach ($items as $key => $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="text-start">{{ $item->id }}</td>
                                <td class="text-start">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}
                                </td>
                                <td class="text-start">{{ $item->customer->name }}</td>
                                <td class="text-start">{{ $item->estimated_amount }}</td>
                                <td class="text-start">
                                    @if ($item->status === \App\Utility\Enums\InquiryStatusEnum::Open->value && $canEdit)
                                        <select class="form-select form-select-solid" {{-- wire:change="changeStatus({{ $item->id }}, $event.target.value)" --}} x-data
                                            {{-- x-on:change="if (confirm('Are you sure you want to change the status to ' + $event.target.value + '?')) {
                                                $wire.changeStatus({{ $item->id }}, $event.target.value)
                                            } else {
                                                $event.target.value = '{{ $item->status }}'
                                            }" --}}
                                            x-on:change="showConfirmationAlert({{ $item->id }}, $event, '{{ $item->status }}')">
                                            @foreach (\App\Utility\Enums\InquiryStatusEnum::cases() as $status)
                                                <option value="{{ $status->value }}" @selected($item->status === $status->value)>
                                                    {{ $status->getName() }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        @if ($item->status === \App\Utility\Enums\InquiryStatusEnum::Cancelled->value)
                                            <span style="cursor: pointer"
                                                x-on:click="showCancellationReason(@js($item->cancellation_reason ?? ''))">
                                                {!! \App\Utility\Enums\InquiryStatusEnum::from($item->status)->getLabel() !!}
                                            </span>
                                        @else
                                            <span>{!! \App\Utility\Enums\InquiryStatusEnum::from($item->status)->getLabel() !!}</span>
                                        @endif
                                    @endif
                                </td>
                                @if ($canEdit || $canDelete)
                                    <td>
                                        <x-panel::table.action.main>
                                            @if ($canEdit && $item->status === \App\Utility\Enums\InquiryStatusEnum::Open->value)
                                                <x-panel::table.action.edit :route="route('company.inquiry.edit', $item->id)"
                                                    module="{{ __('company.inquiry') }}" />
                                            @else
                                                <i class="fa-solid fa-pencil icon edit-icon text-secondary"
                                                    style="cursor: not-allowed"></i>
                                            @endif

                                            {{-- @if ($canDelete)
                                                <a style="cursor: pointer"
                                                    wire:confirm="Are you sure you want delete this"
                                                    wire:click="delete({{ $item->id }})" data-toggle="tooltip"
                                                    data-placement="top" title="Delete Inquiry Details">
                                                    <i class="fa-solid fa-trash icon text-danger"></i>
                                                </a>
                                            @endif --}}
                                            <x-panel::table.action.print :route="route('company.inquiry.view', $item->id)"
                                                module="{{ __('company.inquiry') }}" />
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
