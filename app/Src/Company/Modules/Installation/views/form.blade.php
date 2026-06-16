<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <form class="form" method="post" wire:submit="save">
            @csrf
            <div class="card-body border-top p-6">

                <div class="row mb-6">
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold fs-6 required" for="installationDate">Installation Date</label>
                        <input type="date" id="installationDate" wire:model.blur="form.installationDate"
                               class="form-control form-control-lg form-control-solid">
                        <x-panel::error name="form.installationDate"/>
                    </div>
                </div>

                <h4 class="fw-bold mb-5">Parameters</h4>
                @if($parameters->isEmpty())
                    <p class="text-muted mb-6">No parameters found. Please add parameters first.</p>
                @else
                    <div class="table-responsive mb-8">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-200px">Parameter</th>
                                    <th class="min-w-150px">Value</th>
                                    <th class="min-w-100px">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parameters as $parameter)
                                    <tr wire:key="parameter-{{ $parameter->id }}">
                                        <td class="fw-semibold">{{ $parameter->name }}</td>
                                        <td>
                                            <input type="number" step="any"
                                                   wire:model.blur="form.parameterValues.{{ $parameter->id }}"
                                                   class="form-control form-control-solid"
                                                   placeholder="Enter value">
                                            <x-panel::error name="form.parameterValues.{{ $parameter->id }}"/>
                                        </td>
                                        <td class="text-muted">{{ $parameter->unit }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <h4 class="fw-bold mb-5">Treatment Schemes</h4>
                @if($treatmentSchemes->isEmpty())
                    <p class="text-muted mb-6">No treatment schemes found. Please add treatment schemes first.</p>
                @else
                    <div class="table-responsive mb-8">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-200px">Treatment Scheme</th>
                                    <th class="min-w-150px">Make</th>
                                    <th class="min-w-150px">Model</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($treatmentSchemes as $scheme)
                                    <tr wire:key="scheme-{{ $scheme->id }}">
                                        <td class="fw-semibold">{{ $scheme->name }}</td>
                                        <td>
                                            <input type="text" maxlength="100"
                                                   wire:model.blur="form.schemeDetails.{{ $scheme->id }}.make"
                                                   class="form-control form-control-solid"
                                                   placeholder="Make">
                                            <x-panel::error name="form.schemeDetails.{{ $scheme->id }}.make"/>
                                        </td>
                                        <td>
                                            <input type="text" maxlength="100"
                                                   wire:model.blur="form.schemeDetails.{{ $scheme->id }}.model"
                                                   class="form-control form-control-solid"
                                                   placeholder="Model">
                                            <x-panel::error name="form.schemeDetails.{{ $scheme->id }}.model"/>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <h4 class="fw-bold mb-5">Signature</h4>
                <div class="row mb-6">
                    <div class="col-md-6">
                        <label class="col-form-label fw-semibold fs-6 required">M/s (Client)</label>
                        <p class="text-muted fs-7 mb-3">Sign with finger or stylus in the box below (works on mobile).</p>
                        <div wire:ignore class="signature-pad-wrapper border rounded bg-white position-relative">
                            <canvas id="clientSignatureCanvas" class="w-100 d-block" style="height: 180px; touch-action: none; cursor: crosshair;"></canvas>
                            <span class="position-absolute bottom-0 start-0 end-0 border-top text-muted fs-8 px-3 py-1 user-select-none" style="pointer-events: none;">Sign here</span>
                        </div>
                        <button type="button" id="clearClientSignature" class="btn btn-sm btn-light mt-2">Clear signature</button>
                        <input type="hidden" wire:model="form.clientSignature">
                        <x-panel::error name="form.clientSignature"/>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.installation.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('app.panel.submit') }}</button>
            </div>
        </form>
    </div>
</div>
@script
<script>
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
