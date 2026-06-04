@php
    $labels = App\Utility\Enums\CompanyRegistorTypeEnum::label();
@endphp
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" /> -->
<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{$title}}</h3>
            </div>
        </div>
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
            <div class="row">
    <div class="d-flex justify-content-end align-items-center">
    <span class="{{ $form->status ? 'text-success' : 'text-danger' }} me-2">
            {{ $form->status ? 'Active' : 'Inactive' }}
        </span>
        <label class="form-check form-switch form-check-custom status_switch me-2">
            <input 
                class="form-check-input form-check-button w-45px h-25px"  
                wire:model="form.status" 
                type="checkbox"
                {{ $form->status ? 'checked' : '' }}>
        </label>
       
    </div>
</div>
                <div class="row mb-6">
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('admin.input.company_name')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="name"
                                       name="name" wire:model.blur="form.name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.company_name')]) }}">
                                <x-panel::error name="form.name"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="email">{{ __('admin.input.email')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="email"
                                       name="email" wire:model.blur="form.email"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.email')]) }}">
                                <x-panel::error name="form.email"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="contact_number">{{ __('admin.input.contact_number')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="contact_number"
                                       name="contactNumber" wire:model.blur="form.contactNumber"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.contact_number')]) }}">
                                <x-panel::error name="form.contactNumber"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('admin.input.company_reg_type')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model="form.companyRegistorType" id="company_reg_type">
                                <option value="">{{ __('admin.placeholder.select', ['name' => __('admin.input.company_reg_type')]) }}</option>
                                @foreach(App\Utility\Enums\CompanyRegistorTypeEnum::cases() as $case)
                                    <option value="{{ $case->name }}">{{  $labels[$case->name] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.companyRegistorType"/>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="gstNo">{{ __('admin.input.gst_no')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="gst_no"
                                       name="gstNo" wire:model.blur="form.gstNo"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.gst_no')]) }}">
                                <x-panel::error name="form.gstNo"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="cin_number">{{ __('admin.input.cin_number')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="cin_number"
                                    maxlength="30"
                                    name="cin_number" wire:model.blur="form.cinNumber"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.cin_number')]) }}">
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="password">{{ __('admin.input.password')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="password"
                                    name="password" wire:model.blur="form.password"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.password')]) }}">
                                <x-panel::error name="form.password"/>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="row mb-6">
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="address">{{ __('admin.input.address')}}</label>
                            <div class="col-lg-12 fv-row">
                            <textarea 
                            id="address"
                            name="address"
                            wire:model.blur="form.address"
                            class="form-control form-control-lg form-control-solid"
                            placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.address')]) }}"
                            maxlength="200">
                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="city">{{ __('admin.input.city')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="city"
                                    maxlength="30"
                                    name="city" wire:model.blur="form.city"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.city')]) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="state">{{ __('admin.input.state')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="state"
                                    maxlength="30"
                                    name="state" wire:model.blur="form.state"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.state')]) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="pincode">{{ __('admin.input.pincode')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="pincode"
                                    maxlength="30"
                                    name="pincode" wire:model.blur="form.pincode"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.pincode')]) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-6">
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="company_logo">{{ __('admin.input.company_logo')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="file" accept="image/*" id="company_logo"
                                       name="companyLogo" wire:model.blur="form.companyLogo"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.company_logo')]) }}">
                                <x-panel::error name="form.companyLogo"/>
                            </div>
                        </div>
                    </div>
                    @if($existingLogo )
                    <div class="mt-3">
                        <img src="{{ $existingLogo }}" alt="Company Logo" style="max-width: 100px; height: auto;">
                    </div>
                @endif
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('admin.company.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
                <button type="submit" class="btn btn-primary">{{__('app.panel.submit')}}</button>
            </div>
        </form>
    </div>
</div>


@script
<script>
   $(document).ready(function () {
        $('.add-select2').select2();
        $('.add-select2 option:first-child').prop('disabled', true);

        $('.add-select2').on('change', function (e) {
            var selectedRegType = $('#company_reg_type option:selected').val();
            @this.set('form.companyRegistorType', selectedRegType);
        });



    });


    
</script>
@endscript




