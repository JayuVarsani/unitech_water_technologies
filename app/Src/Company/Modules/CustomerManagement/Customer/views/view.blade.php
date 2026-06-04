<div x-data="custgroup">
    <x-panel::alert />
    <div class="card mb-2 mb-xl-4">
        <x-panel::loader target="save" />

        <div class="card-header border-0">
            <div class="card-title m-0 d-flex align-items-center gap-8 border-bottom border-gray-200">
                <h4 class="m-0 fw-bold fs-5 text-gray-900">{{ __('Customer Details') }}</h4>
            </div>
        </div>
        <div class="card-body border-top p-6">
            <div class="row g-6">
                <div class="col-lg-6">
                    <div class="card-flush h-100">
                        <div class="card-body py-0 px-0">
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-profile-user fs-4 text-gray-700"></i>
                                    </span>
                                    <span class="text-muted fw-semibold fs-7">{{ __('company.input.name') }}</span>
                                </div>
                                <span class="text-gray-900 fw-bold fs-6">{{ $this->customer->name ?: '-' }}</span>
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-sms fs-4 text-gray-700"></i>
                                    </span>
                                    <span class="text-muted fw-semibold fs-7">{{ __('company.input.email') }}</span>
                                </div>
                                <span class="text-gray-900 fw-semibold fs-6">{{ $this->customer->email ?: '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-phone fs-4 text-gray-700"></i>
                                    </span>
                                    <span
                                        class="text-muted fw-semibold fs-7">{{ __('company.input.contact_number') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-semibold fs-6">{{ $this->customer->contact_number ?: '-' }}</span>
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-top border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-whatsapp fs-4 text-gray-700"></i>
                                    </span>
                                    <span
                                        class="text-muted fw-semibold fs-7">{{ __('company.input.whatsapp_number') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-semibold fs-6">{{ $this->customer->whatsapp_number ?: '-' }}</span>
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-people fs-4 text-gray-700"></i>
                                    </span>
                                    <span
                                        class="text-muted fw-semibold fs-7">{{ __('company.input.customer_group') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-semibold fs-6">{{ $this->customer->customer_group_name ?: '-' }}</span>
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-notification-status fs-4 text-gray-700"></i>
                                    </span>
                                    <span
                                        class="text-muted fw-semibold fs-7">{{ __('company.input.auto_reminder') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-semibold fs-6">{{ App\Utility\Enums\AutoReminderTypeEnum::label()[$this->customer->auto_reminder] ?? '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-dollar fs-4 text-gray-700"></i>
                                    </span>
                                    <span
                                        class="text-muted fw-semibold fs-7">{{ __('company.input.opening_balance') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-bold fs-6">{{ $this->customer->opening_balance ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-flush h-100">
                        <div class="card-body py-0 px-0">
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-home-3 fs-4 text-gray-700"></i>
                                    </span>
                                    <span class="text-muted fw-semibold fs-7">{{ __('company.input.address') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-semibold fs-6">{{ $this->customer->address ?: '-' }}</span>
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-geolocation-home fs-4 text-gray-700"></i>
                                    </span>
                                    <span class="text-muted fw-semibold fs-7">{{ __('company.input.city') }}</span>
                                </div>
                                <span class="text-gray-900 fw-semibold fs-6">{{ $this->customer->city ?: '-' }}</span>
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between py-3 border-bottom border-gray-200">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-map fs-4 text-gray-700"></i>
                                    </span>
                                    <span class="text-muted fw-semibold fs-7">{{ __('company.input.state') }}</span>
                                </div>
                                <span class="text-gray-900 fw-semibold fs-6">{{ $this->customer->state ?: '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="w-35px h-35px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                        <i class="ki-outline ki-geolocation fs-4 text-gray-700"></i>
                                    </span>
                                    <span class="text-muted fw-semibold fs-7">{{ __('company.input.pincode') }}</span>
                                </div>
                                <span
                                    class="text-gray-900 fw-semibold fs-6">{{ $this->customer->pincode ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5 mb-xl-10" x-data="{ activeTab: 'products' }">
        <div class="card-header border-0 pt-6 pb-0">
            <div class="card-title m-0 d-flex align-items-center gap-8 border-bottom border-gray-200">
                <button type="button"
                    class="btn btn-sm d-flex align-items-center gap-2 px-0 py-3 rounded-0 border-0 bg-transparent fw-semibold fs-6 position-relative"
                    x-on:click="activeTab = 'products'"
                    :class="activeTab === 'products' ? 'text-gray-900' : 'text-gray-500'">
                    <i class="fa-solid fa-box-open fs-4"
                        :class="activeTab === 'products' ? 'text-gray-900' : 'text-gray-500'"></i>
                    <span>{{ __('company.products') }}</span>
                    <span x-show="activeTab === 'products'" x-cloak
                        class="position-absolute start-0 end-0 bottom-0 h-2px bg-primary rounded"></span>
                </button>
                <button type="button"
                    class="btn btn-sm d-flex align-items-center gap-2 px-0 py-3 rounded-0 border-0 bg-transparent fw-semibold fs-6 position-relative"
                    x-on:click="activeTab = 'orders'"
                    :class="activeTab === 'orders' ? 'text-gray-900' : 'text-gray-500'">
                    <i class="fa-solid fa-cart-plus fs-4"
                        :class="activeTab === 'orders' ? 'text-gray-900' : 'text-gray-500'"></i>
                    <span>{{ __('company.orders') }}</span>
                    <span x-show="activeTab === 'orders'" x-cloak
                        class="position-absolute start-0 end-0 bottom-0 h-2px bg-primary rounded"></span>
                </button>
            </div>
        </div>
        <div class="card-body border-top border-gray-200 p-6">
            <div x-show="activeTab === 'products'">
                <table class="table table-striped">
                    <thead>
                        <tr class="border-bottom text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px text-start text-black">Product Name</th>
                            <th class="min-w-125px text-start text-black">Price</th>
                            <th class="min-w-125px text-start text-black">Default Price</th>
                            <th class="min-w-125px text-start text-black">Special Price</th>
                            <th class="min-w-125px text-start text-black">Product Minimum Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->customer->products as $product)
                            <tr class="border-bottom">
                                <td class="min-w-125px text-start text-black">{{ $product->name ?? 'N/A' }}</td>
                                <td class="min-w-125px text-start text-black">
                                    {{ $product->pivot->product_price_new ?? 'N/A' }}</td>
                                <td class="min-w-125px text-start text-black">
                                    {{ $product->pivot->product_price ?? 'N/A' }}</td>
                                <td class="min-w-125px text-start text-black">
                                    {{ $product->pivot->customer_min_amount ?? 'N/A' }}</td>
                                <td class="min-w-125px text-start text-black">{{ $product->minimum_price ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div x-show="activeTab === 'orders'">
                <table class="table table-striped">
                    <thead>
                        <tr class="border-bottom text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px text-start text-black">{{ __('company.id') }}</th>
                            <th class="min-w-125px text-start text-black">{{ __('company.input.date') }}</th>
                            <th class="min-w-125px text-start text-black">{{ __('company.job_no') }}</th>
                            <th class="min-w-125px text-start text-black">{{ __('company.job_count') }}</th>
                            <th class="min-w-125px text-start text-black">{{ __('company.input.total_amount') }}</th>
                            <th class="min-w-125px text-start text-black">{{ __('company.input.status') }}</th>
                            @if ($this->canEdit)
                                <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->customer->orders->sortByDesc('id') as $order)
                            <tr class="border-bottom">
                                <td class="min-w-125px text-start text-black">{{ $order->id }}</td>
                                <td class="min-w-125px text-start text-black">
                                    {{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
                                <td class="min-w-125px text-start text-black">
                                    {{ $order->orderJobs->pluck('job_no')->implode(', ') }}</td>
                                <td class="min-w-125px text-start text-black">{{ $order->orderJobs->count() }}</td>
                                <td class="min-w-125px text-start text-black">{{ $order->total_amount }}</td>
                                <td class="min-w-125px text-start text-black">{!! \App\Utility\Enums\OrderStatusEnum::tryFrom($order->status)?->getLabel() !!}</td>
                                @if ($this->canEdit)
                                    <td>
                                        <x-panel::table.action.main>
                                            @if ($order->status !== \App\Utility\Enums\OrderStatusEnum::Completed->value)
                                                <x-panel::table.action.edit :route="route('company.order.edit', $order->id)"
                                                    module="{{ __('company.order') }}" />
                                            @else
                                                <a style="cursor: not-allowed" data-toggle="tooltip"
                                                    data-placement="top" title="Edit Order Details">
                                                    <i class="fa-solid fa-pencil icon text-secondary"></i>
                                                </a>
                                            @endif
                                        </x-panel::table.action.main>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
