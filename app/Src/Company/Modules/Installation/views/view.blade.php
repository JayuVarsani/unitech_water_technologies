<div>
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <div class="card-body border-top p-6">

            <div class="text-center mb-8">
                <h2 class="fw-bold mb-1">Unitech Water Technologies</h2>
                <p class="text-muted mb-0">Installation Report</p>
            </div>

            <div class="row mb-6">
                <div class="col-md-4 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Installation Date</div>
                    <div class="fw-bold fs-6">{{ $installation->installation_date?->format('d-m-Y') ?? '-' }}</div>
                </div>
            </div>

            <h4 class="fw-bold mb-5">Parameters</h4>
            @if($installation->installationParameters->isEmpty())
                <p class="text-muted mb-6">-</p>
            @else
                <div class="table-responsive mb-8">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>Parameter</th>
                                <th>Value</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($installation->installationParameters as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->parameter?->name ?? '-' }}</td>
                                    <td>{{ $item->value }}</td>
                                    <td class="text-muted">{{ $item->parameter?->unit ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h4 class="fw-bold mb-5">Treatment Schemes</h4>
            @if($installation->installationTreatmentSchemes->isEmpty())
                <p class="text-muted mb-6">-</p>
            @else
                <div class="table-responsive mb-8">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>Treatment Scheme</th>
                                <th>Make</th>
                                <th>Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($installation->installationTreatmentSchemes as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->treatmentScheme?->name ?? '-' }}</td>
                                    <td>{{ $item->make ?: '-' }}</td>
                                    <td>{{ $item->model ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h4 class="fw-bold mb-5">Signature</h4>
            <div class="row mb-6">
                <div class="col-md-6 mb-4">
                    @if($installation->client_signature)
                        <img src="{{ $installation->client_signature }}" alt="Client signature"
                             class="border rounded bg-white w-100" style="max-height: 180px; object-fit: contain;">
                    @else
                        <div class="fw-bold fs-6">-</div>
                    @endif
                </div>
            </div>

        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('company.installation.index') }}"
               class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.back') }}</a>
            @if($canEdit)
                <a href="{{ route('company.installation.edit', $installation->id) }}"
                   class="btn btn-primary">{{ __('app.panel.edit') }}</a>
            @endif
        </div>
    </div>
</div>
