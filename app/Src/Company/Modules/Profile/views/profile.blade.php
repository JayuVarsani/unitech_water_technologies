<div>
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save" />
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6"
                           for="thumb_image">{{ __('company.input.profile_image') }}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="file"
                               id="thumb_image"
                               accept="image/*"
                               name="form[thumbImage]"
                               wire:model="form.thumbImage"
                               class="form-control form-control-lg">
                        <x-panel::error name="form.thumbImage" />
                        @if ($existingProfileImage)
                            <div class="mt-3">
                                <img src="{{ $existingProfileImage }}" alt="{{ __('company.input.profile_image') }}"
                                     style="max-width: 100px; height: auto;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                           for="name">{{ __('company.input.name') }}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text"
                               id="name"
                               name="form[name]"
                               class="form-control form-control-lg form-control-solid"
                               wire:model.blur="form.name"
                               onblur="this.value = this.value.trim()"
                               placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.name')]) }}">
                        <x-panel::error name="form.name" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                           for="email">{{ __('company.input.email') }}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text"
                               id="email"
                               name="form[email]"
                               wire:model.blur="form.email"
                               onblur="this.value = this.value.trim()"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.email')]) }}">
                        <x-panel::error name="form.email" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                           for="contactNumber">{{ __('company.input.contact_number') }}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="tel"
                               id="contactNumber"
                               name="form[contactNumber]"
                               autocomplete="off"
                               wire:model.blur="form.contactNumber"
                               class="form-control form-control-lg form-control-solid"
                               placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.contact_number')]) }}">
                        <x-panel::error name="form.contactNumber" />
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.dashboard') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{ __('app.panel.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('app.panel.submit') }}</button>
            </div>
        </form>
    </div>
</div>
