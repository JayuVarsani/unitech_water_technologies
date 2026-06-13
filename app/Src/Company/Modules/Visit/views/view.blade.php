<div>
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <div class="card-body border-top p-6">

            <div class="text-center mb-8">
                <h2 class="fw-bold mb-1">Unitech Water Technologies</h2>
                <p class="text-muted mb-0">Service Report — Reverse Osmosis (RO) Plant</p>
            </div>

            <h4 class="fw-bold mb-5">General Details</h4>
            <div class="row mb-6">
                <div class="col-md-3 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Date of Visit</div>
                    <div class="fw-bold fs-6">{{ $visit->visit_date?->format('d-m-Y') ?? '-' }}</div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">No. of Visit</div>
                    <div class="fw-bold fs-6">{{ $visit->visit_number ?: '-' }}</div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Representative / Branch</div>
                    <div class="fw-bold fs-6">{{ $visit->representative ?: '-' }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Name of Site</div>
                    <div class="fw-bold fs-6">{{ $visit->site_name ?: '-' }}</div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Contact Person</div>
                    <div class="fw-bold fs-6">{{ $visit->contact_person ?: '-' }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <div class="col-md-12 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Contact Address</div>
                    <div class="fw-bold fs-6">{{ $visit->contact_address ?: '-' }}</div>
                </div>
            </div>

            <div class="row mb-8">
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">Plant Specification (RO Capacity LPH)</div>
                    <div class="fw-bold fs-6">{{ filled($visit->plant_capacity_lph) ? $visit->plant_capacity_lph : '-' }}</div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">AMC Period</div>
                    <div class="fw-bold fs-6">{{ $visit->amc_period ?: '-' }}</div>
                </div>
            </div>

            <h4 class="fw-bold mb-5">Equipment Status &amp; Checklist</h4>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">1. Raw Water Pump</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Current (Amps)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->raw_water_pump_amps) ? $visit->raw_water_pump_amps : '-' }}</div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Make</div>
                        <div class="fw-bold fs-6">{{ $visit->raw_water_pump_make ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">2. Multi Grade Filter / Dual Media Filter</h5>
                <div class="text-muted fs-7 fw-semibold mb-1">Backwash Done Properly</div>
                <div class="fw-bold fs-6">{{ $visit->mgf_backwash_done ? ucfirst($visit->mgf_backwash_done) : '-' }}</div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">3. Activated Carbon Filter</h5>
                <div class="text-muted fs-7 fw-semibold mb-1">Backwash Done Properly</div>
                <div class="fw-bold fs-6">{{ $visit->acf_backwash_done ? ucfirst($visit->acf_backwash_done) : '-' }}</div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">4. Dosing Pump</h5>
                <div class="text-muted fs-7 fw-semibold mb-1">Working Properly</div>
                <div class="fw-bold fs-6">{{ $visit->dosing_pump_working ? ucfirst($visit->dosing_pump_working) : '-' }}</div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">5. Antiscalent Chemical</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Make</div>
                        <div class="fw-bold fs-6">{{ $visit->antiscalent_make ?: '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Dosage (PPM)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->antiscalent_dosage_ppm) ? $visit->antiscalent_dosage_ppm : '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">6. Micron Cartridge Filter</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Replaced Properly</div>
                        <div class="fw-bold fs-6">{{ $visit->mcf_replaced ? ucfirst($visit->mcf_replaced) : '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Last Replaced</div>
                        <div class="fw-bold fs-6">{{ $visit->mcf_last_replaced?->format('d-m-Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Size</div>
                        <div class="fw-bold fs-6">{{ $visit->mcf_size ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">7. HPS / LPS (High/Low Pressure Switch)</h5>
                <div class="text-muted fs-7 fw-semibold mb-1">Working Properly</div>
                <div class="fw-bold fs-6">{{ $visit->hps_lps_working ? ucfirst($visit->hps_lps_working) : '-' }}</div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">8. High Pressure Pump</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Current (Amps)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->hpp_amps) ? $visit->hpp_amps : '-' }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Feed Pressure (kg/cm²)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->hpp_feed_pressure) ? $visit->hpp_feed_pressure : '-' }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Reject Pressure (kg/cm²)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->hpp_reject_pressure) ? $visit->hpp_reject_pressure : '-' }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Make &amp; Model</div>
                        <div class="fw-bold fs-6">{{ $visit->hpp_make_model ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">9. System Reading</h5>
                <div class="row mb-2">
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Raw Water TDS</div>
                        <div class="fw-bold fs-6">{{ filled($visit->reading_raw_tds) ? $visit->reading_raw_tds : '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Product Water TDS / Conductivity</div>
                        <div class="fw-bold fs-6">{{ filled($visit->reading_product_tds) ? $visit->reading_product_tds : '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Feed Flow Rate (Lit/hr)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->reading_feed_flow) ? $visit->reading_feed_flow : '-' }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Product Flow Rate (Lit/hr)</div>
                        <div class="fw-bold fs-6">{{ filled($visit->reading_product_flow) ? $visit->reading_product_flow : '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Feed pH</div>
                        <div class="fw-bold fs-6">{{ filled($visit->reading_feed_ph) ? $visit->reading_feed_ph : '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-muted fs-7 fw-semibold mb-1">Product pH</div>
                        <div class="fw-bold fs-6">{{ filled($visit->reading_product_ph) ? $visit->reading_product_ph : '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="border rounded p-5 mb-6">
                <h5 class="fw-semibold mb-4">10. Remarks</h5>
                <div class="fw-bold fs-6">{{ $visit->remarks ?: '-' }}</div>
            </div>

            <h4 class="fw-bold mb-5">Signatures</h4>
            <div class="row mb-6">
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-2">M/s (Client)</div>
                    @if($visit->client_signature)
                        <img src="{{ $visit->client_signature }}" alt="Client signature"
                             class="border rounded bg-white w-100" style="max-height: 180px; object-fit: contain;">
                    @else
                        <div class="fw-bold fs-6">-</div>
                    @endif
                </div>
                <div class="col-md-6 mb-4">
                    <div class="text-muted fs-7 fw-semibold mb-1">M/s. Unitech Water Technologies (Service)</div>
                    <div class="fw-bold fs-6">{{ $visit->technician_signature_name ?: '-' }}</div>
                </div>
            </div>

        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('company.visit.index') }}"
               class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.back') }}</a>
            @if($canEdit)
                <a href="{{ route('company.visit.edit', $visit->id) }}"
                   class="btn btn-primary">{{ __('app.panel.edit') }}</a>
            @endif
        </div>
    </div>
</div>
