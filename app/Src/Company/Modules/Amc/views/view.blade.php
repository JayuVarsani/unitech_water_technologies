<div>
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <div class="card-body border-top p-6">

            <div class="text-center mb-8">
                <h2 class="fw-bold mb-1">Unitech Water Technologies</h2>
                <p class="text-muted mb-0">AMC Details</p>
            </div>

            <div class="row mb-6">
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Company</div>
                    <div class="fw-bold fs-6">{{ $amc->customer_name ?: '-' }}</div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">From Date</div>
                    <div class="fw-bold fs-6">{{ $amc->from_date?->format('d-m-Y') ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">To Date</div>
                    <div class="fw-bold fs-6">{{ $amc->to_date?->format('d-m-Y') ?? '-' }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Visit Count</div>
                    <div class="fw-bold fs-6">{{ $amc->visit_count }}</div>
                </div>
            </div>

            <h4 class="fw-bold mb-5">Visit Months</h4>
            @if($amc->visitMonths->isEmpty())
                <p class="text-muted mb-6">-</p>
            @else
                <div class="row mb-6">
                    @foreach($amc->visitMonths as $index => $visitMonth)
                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-7 fw-semibold mb-1">Visit {{ $index + 1 }}</div>
                            <div class="fw-bold fs-6">{{ $visitMonth->visit_month?->format('F Y') ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('company.amc.index') }}"
               class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.back') }}</a>
            @if($canEdit)
                <a href="{{ route('company.amc.edit', $amc->id) }}"
                   class="btn btn-primary">{{ __('app.panel.edit') }}</a>
            @endif
        </div>
    </div>
</div>
