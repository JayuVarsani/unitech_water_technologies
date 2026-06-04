<div x-data='table'>
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
                            placeholder="{{ __('app.panel.search_name', ['name' => __('company.wastage')]) }}">
                    </div>
                </div>
            @endif
        </div>
        @if ($canView)
            <div class="card-body pt-0">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <th class="min-w-125px text-start text-black">{{ __('company.id') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.jobno') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.date') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.print_by') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.damage_type') }}</th>
                        <th class="min-w-125px text-start text-black">{{ __('company.input.note') }}</th>
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach ($items as $key => $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="text-start">{{ $item->id }}</td>
                                <td class="text-start">{{ $item->orderJob->job_no ?? '-' }}</td>
                                <td class="text-start">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td class="text-start">{{ $item->orderJob->printBy->name ?? '-' }}</td>
                                <td class="text-start">{!! $item->damage_type ? \App\Utility\Enums\DamageTypeEnum::tryFrom($item->damage_type)?->getName() : 'N/A' !!}</td>
                                <td class="text-start">{{ $item->note ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </x-panel::table.body>
                </x-panel::table.main>
            </div>
        @endif
    </div>
</div>
@script
    <script></script>
@endscript
