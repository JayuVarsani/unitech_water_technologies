<div>
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <div class="card-body border-top p-6">

            <div class="text-center mb-8">
                <h2 class="fw-bold mb-1">Unitech Water Technologies</h2>
                <p class="text-muted mb-0">Delivery Challan</p>
                @if($delivery->entry_type === 'image')
                    <span class="badge badge-light-info mt-2">Transport / Parcel</span>
                @else
                    <span class="badge badge-light-primary mt-2">Direct Delivery</span>
                @endif
            </div>

            <div class="row mb-6">
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Delivery Date</div>
                    <div class="fw-bold fs-6">{{ $delivery->delivery_date?->format('d-m-Y') ?? '-' }}</div>
                </div>
                <div class="col-md-8 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Customer</div>
                    <div class="fw-bold fs-6">{{ $delivery->customer_name ?: '-' }}</div>
                </div>
            </div>

            
            <div class="row mb-8">
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Final Amount</div>
                    <div class="fw-bold fs-6">₹ {{ number_format((float) $delivery->final_amount, 2) }}</div>
                </div>
            </div>

            @if($delivery->entry_type === 'image')
                <h4 class="fw-bold mb-5">Delivery Challan Image</h4>
                <div class="row mb-6">
                    <div class="col-md-8 mb-4">
                        @php $challanImage = $delivery->getFirstMediaUrl('delivery_challan'); @endphp
                        @if($challanImage)
                            <a href="{{ $challanImage }}" target="_blank">
                                <img src="{{ $challanImage }}" alt="Delivery challan"
                                     class="border rounded bg-white w-100" style="max-height: 480px; object-fit: contain;">
                            </a>
                        @else
                            <div class="fw-bold fs-6">-</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="row mb-6">
                    <div class="col-md-12 mb-4">
                        <div class="text-muted fs-7 fw-semibold mb-1">Item Details</div>
                        <div class="fw-bold fs-6" style="white-space: pre-line;">{{ $delivery->item_details ?: '-' }}</div>
                    </div>
                </div>

                <h4 class="fw-bold mb-5">Customer Signature</h4>
                <div class="row mb-6">
                    <div class="col-md-6 mb-4">
                        @if($delivery->customer_signature)
                            <img src="{{ $delivery->customer_signature }}" alt="Customer signature"
                                 class="border rounded bg-white w-100" style="max-height: 180px; object-fit: contain;">
                        @else
                            <div class="fw-bold fs-6">-</div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('company.delivery.index') }}"
               class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.back') }}</a>
            @if($canEdit)
                <a href="{{ route('company.delivery.edit', $delivery->id) }}"
                   class="btn btn-primary">{{ __('app.panel.edit') }}</a>
            @endif
        </div>
    </div>
</div>
