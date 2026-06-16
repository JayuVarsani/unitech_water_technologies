<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row mb-6">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="name">{{ __('company.input.name')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="name"
                                       maxlength="100"
                                       onblur="this.value = this.value.trim()"
                                       name="name" wire:model.blur="form.name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="e.g. Raw water pump">
                                <x-panel::error name="form.name"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="unit">Unit</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="unit"
                                       maxlength="50"
                                       onblur="this.value = this.value.trim()"
                                       name="unit" wire:model.blur="form.unit"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="e.g. kg/cm2">
                                <x-panel::error name="form.unit"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.parameter.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
                <button type="submit" class="btn btn-primary">{{__('app.panel.submit')}}</button>
            </div>
        </form>
    </div>
</div>
