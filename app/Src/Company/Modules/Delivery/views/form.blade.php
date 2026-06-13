<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <div class="card-header border-0 pt-6 pb-0">
            <div class="card-title m-0 d-flex align-items-center gap-8 border-bottom border-gray-200">
                <button type="button"
                        class="btn btn-sm d-flex align-items-center gap-2 px-0 py-3 rounded-0 border-0 bg-transparent fw-semibold fs-6 position-relative"
                        wire:click="setEntryType('form')"
                        @class([
                            'text-gray-900' => $form->entryType === 'form',
                            'text-gray-500' => $form->entryType !== 'form',
                        ])>
                    <i class="fa-solid fa-file-pen fs-4"
                       @class([
                           'text-gray-900' => $form->entryType === 'form',
                           'text-gray-500' => $form->entryType !== 'form',
                       ])></i>
                    <span>Direct Delivery</span>
                    @if($form->entryType === 'form')
                        <span class="position-absolute start-0 end-0 bottom-0 h-2px bg-primary rounded"></span>
                    @endif
                </button>
                <button type="button"
                        class="btn btn-sm d-flex align-items-center gap-2 px-0 py-3 rounded-0 border-0 bg-transparent fw-semibold fs-6 position-relative"
                        wire:click="setEntryType('image')"
                        @class([
                            'text-gray-900' => $form->entryType === 'image',
                            'text-gray-500' => $form->entryType !== 'image',
                        ])>
                    <i class="fa-solid fa-image fs-4"
                       @class([
                           'text-gray-900' => $form->entryType === 'image',
                           'text-gray-500' => $form->entryType !== 'image',
                       ])></i>
                    <span>Transport / Parcel</span>
                    @if($form->entryType === 'image')
                        <span class="position-absolute start-0 end-0 bottom-0 h-2px bg-primary rounded"></span>
                    @endif
                </button>
            </div>
        </div>
        <form class="form" method="post" wire:submit="save">
            @csrf
            <div class="card-body border-top p-6">

                <div class="row mb-6">
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold fs-6 required" for="deliveryDate">Delivery Date</label>
                        <input type="date" id="deliveryDate" wire:model.blur="form.deliveryDate"
                               class="form-control form-control-lg form-control-solid">
                        <x-panel::error name="form.deliveryDate"/>
                    </div>
                    <div class="col-md-8">
                        <label class="col-form-label fw-semibold fs-6 required" for="customerId">Customer</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2" id="customerId" wire:model.defer="form.customerId">
                                <option value="">Select customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected((string) $form->customerId === (string) $customer->id)>{{ $customer->name }} ({{ $customer->contact_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.customerId"/>
                    </div>
                </div>

                <div class="row mb-6">
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold fs-6 required" for="finalAmount">Final Amount (₹)</label>
                        <input type="number" step="any" id="finalAmount" wire:model.blur="form.finalAmount"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Final amount">
                        <x-panel::error name="form.finalAmount"/>
                    </div>
                </div>

                <div class="{{ $form->entryType !== 'form' ? 'd-none' : '' }}">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="col-form-label fw-semibold fs-6 required" for="itemDetails">Item Details</label>
                            <textarea id="itemDetails" rows="5" wire:model.blur="form.itemDetails"
                                      class="form-control form-control-lg form-control-solid"
                                      placeholder="Item name, quantity, description, etc."></textarea>
                            <x-panel::error name="form.itemDetails"/>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-5">Customer Signature</h4>
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="col-form-label fw-semibold fs-6">M/s (Customer)</label>
                            <p class="text-muted fs-7 mb-3">Sign with finger or stylus in the box below (works on mobile).</p>
                            <div wire:ignore class="signature-pad-wrapper border rounded bg-white position-relative">
                                <canvas id="customerSignatureCanvas" class="w-100 d-block" style="height: 180px; touch-action: none; cursor: crosshair;"></canvas>
                                <span class="position-absolute bottom-0 start-0 end-0 border-top text-muted fs-8 px-3 py-1 user-select-none" style="pointer-events: none;">Sign here</span>
                            </div>
                            <button type="button" id="clearCustomerSignature" class="btn btn-sm btn-light mt-2">Clear signature</button>
                            <input type="hidden" wire:model="form.customerSignature">
                            <x-panel::error name="form.customerSignature"/>
                        </div>
                    </div>
                </div>

                <div class="{{ $form->entryType !== 'image' ? 'd-none' : '' }}">
                    <div class="row mb-6">
                        <div class="col-md-8">
                            <label class="col-form-label fw-semibold fs-6 required" for="deliveryImage">Delivery Challan Image</label>
                            <p class="text-muted fs-7 mb-3">Upload a photo of the challan when delivery is sent via transport or parcel service.</p>
                            <x-panel::form.image-capture key="deliveryImage" :form="$form" name="deliveryImage"
                                                         :prevImage="$form->oldDeliveryImage"/>
                            <x-panel::error name="form.deliveryImage"/>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.delivery.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('app.panel.submit') }}</button>
            </div>
        </form>
    </div>
</div>
@script
<script>
    $(document).ready(function () {
        $('.add-select2').select2();
        $('.add-select2 option:first-child').prop('disabled', true);

        $('#customerId').on('change', function () {
            @this.set('form.customerId', $(this).val());
        });
    });

    (function () {
        const canvas = document.getElementById('customerSignatureCanvas');
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
                $wire.set('form.customerSignature', '');
                return;
            }
            $wire.set('form.customerSignature', canvas.toDataURL('image/png'));
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
            $wire.set('form.customerSignature', '');
        }

        function loadExistingSignature() {
            const existing = $wire.get('form.customerSignature');
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

        document.getElementById('clearCustomerSignature')?.addEventListener('click', clearSignature);

        resizeCanvas();
        loadExistingSignature();

        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                const saved = $wire.get('form.customerSignature');
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
