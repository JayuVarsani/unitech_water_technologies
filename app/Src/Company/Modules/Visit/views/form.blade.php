<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <form class="form" method="post" wire:submit="save">
            @csrf
            <div class="card-body border-top p-6">

                {{-- <div class="text-center mb-8">
                    <h2 class="fw-bold mb-1">Unitech Water Technologies</h2>
                    <p class="text-muted mb-0">Service Report — Reverse Osmosis (RO) Plant</p>
                </div> --}}

                <h4 class="fw-bold mb-5">General Details</h4>
                <div class="row mb-6">
                    <div class="col-md-3">
                        <label class="col-form-label fw-semibold fs-6" for="visitDate">Date of Visit</label>
                        <input type="date" id="visitDate" wire:model.blur="form.visitDate"
                               class="form-control form-control-lg form-control-solid">
                        <x-panel::error name="form.visitDate"/>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label fw-semibold fs-6" for="visitNumber">No. of Visit</label>
                        <input type="text" id="visitNumber" wire:model.blur="form.visitNumber"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Visit number"
                               @if(!empty($completeMode)) readonly @endif>
                        <x-panel::error name="form.visitNumber"/>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6 required" for="representative">Representative / Branch</label>
                        @if(!empty($completeMode))
                            <div wire:ignore class="col-lg-12 fv-row">
                                <select class="form-select form-select-lg add-select2-staff" id="staffId" wire:model.defer="form.staffId">
                                    <option value="">Select staff</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}" @selected((string) $form->staffId === (string) $staff->id)>{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-panel::error name="form.staffId"/>
                        @else
                            <input type="text" id="representative" wire:model.blur="form.representative"
                                   class="form-control form-control-lg form-control-solid"
                                   placeholder="Unitech representative or branch">
                            <x-panel::error name="form.representative"/>
                        @endif
                    </div>
                </div>

                <div class="row mb-6">
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6" for="siteName">Name of Site</label>
                        <input type="text" id="siteName" wire:model.blur="form.siteName"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Site name"
                               @if(!empty($completeMode)) readonly @endif>
                        <x-panel::error name="form.siteName"/>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6" for="contactPerson">Contact Person</label>
                        <input type="text" id="contactPerson" wire:model.blur="form.contactPerson"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Contact person">
                        <x-panel::error name="form.contactPerson"/>
                    </div>
                </div>

                <div class="row mb-6">
                    <div class="col-md-12">
                        <label class="col-form-label fw-semibold fs-6" for="contactAddress">Contact Address</label>
                        <textarea id="contactAddress" rows="2" wire:model.blur="form.contactAddress"
                                  class="form-control form-control-lg form-control-solid"
                                  placeholder="Contact address"></textarea>
                        <x-panel::error name="form.contactAddress"/>
                    </div>
                </div>

                <div class="row mb-8">
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6" for="plantCapacityLph">Plant Specification (RO Capacity LPH)</label>
                        <input type="number" step="any" id="plantCapacityLph" wire:model.blur="form.plantCapacityLph"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Capacity in LPH">
                        <x-panel::error name="form.plantCapacityLph"/>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6" for="amcPeriod">AMC Period</label>
                        <input type="text" id="amcPeriod" wire:model.blur="form.amcPeriod"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="AMC period">
                        <x-panel::error name="form.amcPeriod"/>
                    </div>
                </div>

                <h4 class="fw-bold mb-5">Equipment Status &amp; Checklist</h4>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">1. Raw Water Pump</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="rawWaterPumpAmps">Current (Amps)</label>
                            <input type="number" step="any" id="rawWaterPumpAmps" wire:model.blur="form.rawWaterPumpAmps"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.rawWaterPumpAmps"/>
                        </div>
                        <div class="col-md-8">
                            <label class="col-form-label fw-semibold fs-6" for="rawWaterPumpMake">Make</label>
                            <input type="text" id="rawWaterPumpMake" wire:model.blur="form.rawWaterPumpMake"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.rawWaterPumpMake"/>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">2. Multi Grade Filter / Dual Media Filter</h5>
                    <label class="col-form-label fw-semibold fs-6" for="mgfBackwashDone">Backwash Done Properly</label>
                    <select id="mgfBackwashDone" wire:model.blur="form.mgfBackwashDone"
                            class="form-select form-select-lg form-control-solid">
                        <option value="">Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <x-panel::error name="form.mgfBackwashDone"/>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">3. Activated Carbon Filter</h5>
                    <label class="col-form-label fw-semibold fs-6" for="acfBackwashDone">Backwash Done Properly</label>
                    <select id="acfBackwashDone" wire:model.blur="form.acfBackwashDone"
                            class="form-select form-select-lg form-control-solid">
                        <option value="">Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <x-panel::error name="form.acfBackwashDone"/>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">4. Dosing Pump</h5>
                    <label class="col-form-label fw-semibold fs-6" for="dosingPumpWorking">Working Properly</label>
                    <select id="dosingPumpWorking" wire:model.blur="form.dosingPumpWorking"
                            class="form-select form-select-lg form-control-solid">
                        <option value="">Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <x-panel::error name="form.dosingPumpWorking"/>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">5. Antiscalent Chemical</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-form-label fw-semibold fs-6" for="antiscalentMake">Make</label>
                            <input type="text" id="antiscalentMake" wire:model.blur="form.antiscalentMake"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.antiscalentMake"/>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label fw-semibold fs-6" for="antiscalentDosagePpm">Dosage (PPM)</label>
                            <input type="number" step="any" id="antiscalentDosagePpm" wire:model.blur="form.antiscalentDosagePpm"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.antiscalentDosagePpm"/>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">6. Micron Cartridge Filter</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="mcfReplaced">Replaced Properly</label>
                            <select id="mcfReplaced" wire:model.blur="form.mcfReplaced"
                                    class="form-select form-select-lg form-control-solid">
                                <option value="">Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <x-panel::error name="form.mcfReplaced"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="mcfLastReplaced">Last Replaced</label>
                            <input type="date" id="mcfLastReplaced" wire:model.blur="form.mcfLastReplaced"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.mcfLastReplaced"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="mcfSize">Size</label>
                            <input type="text" id="mcfSize" wire:model.blur="form.mcfSize"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.mcfSize"/>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">7. HPS / LPS (High/Low Pressure Switch)</h5>
                    <label class="col-form-label fw-semibold fs-6" for="hpsLpsWorking">Working Properly</label>
                    <select id="hpsLpsWorking" wire:model.blur="form.hpsLpsWorking"
                            class="form-select form-select-lg form-control-solid">
                        <option value="">Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <x-panel::error name="form.hpsLpsWorking"/>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">8. High Pressure Pump</h5>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="col-form-label fw-semibold fs-6" for="hppAmps">Current (Amps)</label>
                            <input type="number" step="any" id="hppAmps" wire:model.blur="form.hppAmps"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.hppAmps"/>
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label fw-semibold fs-6" for="hppFeedPressure">Feed Pressure (kg/cm²)</label>
                            <input type="number" step="any" id="hppFeedPressure" wire:model.blur="form.hppFeedPressure"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.hppFeedPressure"/>
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label fw-semibold fs-6" for="hppRejectPressure">Reject Pressure (kg/cm²)</label>
                            <input type="number" step="any" id="hppRejectPressure" wire:model.blur="form.hppRejectPressure"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.hppRejectPressure"/>
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label fw-semibold fs-6" for="hppMakeModel">Make &amp; Model</label>
                            <input type="text" id="hppMakeModel" wire:model.blur="form.hppMakeModel"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.hppMakeModel"/>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">9. System Reading</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="readingRawTds">Raw Water TDS</label>
                            <input type="number" step="any" id="readingRawTds" wire:model.blur="form.readingRawTds"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.readingRawTds"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="readingProductTds">Product Water TDS / Conductivity</label>
                            <input type="number" step="any" id="readingProductTds" wire:model.blur="form.readingProductTds"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.readingProductTds"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="readingFeedFlow">Feed Flow Rate (Lit/hr)</label>
                            <input type="number" step="any" id="readingFeedFlow" wire:model.blur="form.readingFeedFlow"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.readingFeedFlow"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="readingProductFlow">Product Flow Rate (Lit/hr)</label>
                            <input type="number" step="any" id="readingProductFlow" wire:model.blur="form.readingProductFlow"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.readingProductFlow"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="readingFeedPh">Feed pH</label>
                            <input type="number" step="any" id="readingFeedPh" wire:model.blur="form.readingFeedPh"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.readingFeedPh"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label fw-semibold fs-6" for="readingProductPh">Product pH</label>
                            <input type="number" step="any" id="readingProductPh" wire:model.blur="form.readingProductPh"
                                   class="form-control form-control-lg form-control-solid">
                            <x-panel::error name="form.readingProductPh"/>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-5 mb-6">
                    <h5 class="fw-semibold mb-4">10. Remarks</h5>
                    <textarea id="remarks" rows="4" wire:model.blur="form.remarks"
                              class="form-control form-control-lg form-control-solid"
                              placeholder="Remarks"></textarea>
                    <x-panel::error name="form.remarks"/>
                </div>

                <h4 class="fw-bold mb-5">Signatures</h4>
                <div class="row mb-6">
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6">M/s (Client)</label>
                        <p class="text-muted fs-7 mb-3">Sign with finger or stylus in the box below (works on mobile).</p>
                        <div wire:ignore class="signature-pad-wrapper border rounded bg-white position-relative">
                            <canvas id="clientSignatureCanvas" class="w-100 d-block" style="height: 180px; touch-action: none; cursor: crosshair;"></canvas>
                            <span class="position-absolute bottom-0 start-0 end-0 border-top text-muted fs-8 px-3 py-1 user-select-none" style="pointer-events: none;">Sign here</span>
                        </div>
                        <button type="button" id="clearClientSignature" class="btn btn-sm btn-light mt-2">Clear signature</button>
                        <input type="hidden" wire:model="form.clientSignature">
                        <x-panel::error name="form.clientSignature"/>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6" for="technicianSignatureName">M/s. Unitech Water Technologies (Service)</label>
                        <input type="text" id="technicianSignatureName" wire:model.blur="form.technicianSignatureName"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Technician name / signature">
                        <x-panel::error name="form.technicianSignatureName"/>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.visit.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ !empty($completeMode) ? 'Complete Visit' : __('app.panel.submit') }}</button>
            </div>
        </form>
    </div>
</div>
@script
<script>
    @if(!empty($completeMode))
    $(document).ready(function () {
        $('.add-select2-staff').select2();
        $('.add-select2-staff option:first-child').prop('disabled', true);

        $('#staffId').on('change', function () {
            @this.set('form.staffId', $(this).val());
        });
    });
    @endif

    (function () {
        const canvas = document.getElementById('clientSignatureCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let drawing = false;
        let hasStroke = false;
        const displayHeight = 180;

        function setupContext() {
            ctx.strokeStyle = '#1e1e2d';
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
        }

        function resizeCanvas() {
            const width = canvas.parentElement.clientWidth || 300;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = Math.floor(width * ratio);
            canvas.height = Math.floor(displayHeight * ratio);
            canvas.style.width = width + 'px';
            canvas.style.height = displayHeight + 'px';
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(ratio, ratio);
            setupContext();
        }

        function getPoint(e) {
            const rect = canvas.getBoundingClientRect();
            const source = e.touches && e.touches.length ? e.touches[0] : e;
            return {
                x: source.clientX - rect.left,
                y: source.clientY - rect.top,
            };
        }

        function syncSignature() {
            if (!hasStroke) {
                $wire.set('form.clientSignature', '');
                return;
            }
            $wire.set('form.clientSignature', canvas.toDataURL('image/png'));
        }

        function startDraw(e) {
            e.preventDefault();
            drawing = true;
            hasStroke = true;
            const point = getPoint(e);
            ctx.beginPath();
            ctx.moveTo(point.x, point.y);
        }

        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            const point = getPoint(e);
            ctx.lineTo(point.x, point.y);
            ctx.stroke();
        }

        function endDraw(e) {
            if (!drawing) return;
            e.preventDefault();
            drawing = false;
            syncSignature();
        }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasStroke = false;
            $wire.set('form.clientSignature', '');
        }

        function loadExistingSignature() {
            const existing = $wire.get('form.clientSignature');
            if (!existing || !existing.startsWith('data:image')) return;

            const img = new Image();
            img.onload = function () {
                resizeCanvas();
                const width = canvas.parentElement.clientWidth || 300;
                ctx.drawImage(img, 0, 0, width, displayHeight);
                hasStroke = true;
            };
            img.src = existing;
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', endDraw);
        canvas.addEventListener('mouseleave', endDraw);
        canvas.addEventListener('touchstart', startDraw, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', endDraw, { passive: false });
        canvas.addEventListener('touchcancel', endDraw, { passive: false });

        document.getElementById('clearClientSignature')?.addEventListener('click', clearSignature);

        resizeCanvas();
        loadExistingSignature();

        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                const saved = $wire.get('form.clientSignature');
                resizeCanvas();
                if (saved && saved.startsWith('data:image')) {
                    const img = new Image();
                    img.onload = function () {
                        const width = canvas.parentElement.clientWidth || 300;
                        ctx.drawImage(img, 0, 0, width, displayHeight);
                        hasStroke = true;
                    };
                    img.src = saved;
                }
            }, 150);
        });
    })();
</script>
@endscript
