<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <form class="form" method="post" wire:submit="save">
            @csrf
            <div class="card-body border-top p-6">

                <div class="row mb-6">
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold fs-6 required" for="fromDate">From Date</label>
                        <input type="date" id="fromDate" wire:model.live="form.fromDate"
                               class="form-control form-control-lg form-control-solid">
                        <x-panel::error name="form.fromDate"/>
                    </div>
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold fs-6 required" for="toDate">To Date</label>
                        <input type="date" id="toDate" wire:model.live="form.toDate"
                               class="form-control form-control-lg form-control-solid">
                        <x-panel::error name="form.toDate"/>
                    </div>
                </div>

                <div class="row mb-6">
                    <div class="col-md-8">
                        <label class="col-form-label fw-semibold fs-6 required" for="customerId">Company</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2" id="customerId" wire:model.defer="form.customerId">
                                <option value="">Select company</option>
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
                        <label class="col-form-label fw-semibold fs-6 required" for="visitCount">Visit Count</label>
                        <input type="number" min="1" max="24" id="visitCount" wire:model.live="form.visitCount"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="Enter visit count">
                        <x-panel::error name="form.visitCount"/>
                    </div>
                </div>

                @if((int) $form->visitCount > 0)
                    <h4 class="fw-bold mb-5">Visit Months</h4>
                    <div class="row mb-6">
                        @for($i = 0; $i < (int) $form->visitCount; $i++)
                            <div class="col-md-4 mb-4" wire:key="visit-month-{{ $i }}">
                                <label class="col-form-label fw-semibold fs-6 required" for="visitMonth{{ $i }}">Visit {{ $i + 1 }}</label>
                                <input type="month" id="visitMonth{{ $i }}"
                                       wire:model.blur="form.visitMonths.{{ $i }}"
                                       class="form-control form-control-lg form-control-solid">
                                <x-panel::error name="form.visitMonths.{{ $i }}"/>
                            </div>
                        @endfor
                    </div>
                @endif

            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.amc.index') }}"
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
</script>
@endscript
