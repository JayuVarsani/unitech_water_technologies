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
                                <input type="file" id="company_logo"
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
   

    $(document).ready(function () 
    {
    function initializeInputNavigation() {
        document.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                const form = event.target.form;
                if (form) {
                    event.preventDefault();
                    const focusableElements = Array.from(
                        form.querySelectorAll(
                            'input, textarea, select, [tabindex]:not([tabindex="-1"])'
                        )
                    );

                    // Handle Select2-specific logic
                    if ($(event.target).hasClass("select2-search__field")) {
                        const select2Element = $(event.target)
                            .closest(".select2-container")
                            .prev("select.add-select2");
                        if (select2Element.length) {
                            select2Element.select2("close");
                            moveToNextElement(form, focusableElements, select2Element[0]);
                            return;
                        }
                    }

                    moveToNextElement(form, focusableElements, event.target);
                }
            }
        });
    }

    function moveToNextElement(form, focusableElements, currentElement) {
        const currentIndex = focusableElements.indexOf(currentElement);
        if (currentIndex === focusableElements.length - 1) {
            form.requestSubmit(); // Submit the form if it's the last element
        } else {
            let nextElement = focusableElements[currentIndex + 1];

            if (nextElement && nextElement.type === "file") {
                     nextElement.click(); // Open file selection window
                 }else {
                    nextElement.focus();
                }


            // Skip the Select2 span wrapper if nextElement is not the select itself
            if ($(nextElement).hasClass('select2-selection') || $(nextElement).hasClass('select2-selection--single')) {
                nextElement = focusableElements[currentIndex + 2] || nextElement;
            }

            // Check if the next element is a Select2 element
            if ($(nextElement).hasClass("add-select2")) {
                if ($.fn.select2 && $(nextElement).data("select2")) {
                    console.log("Opening Select2 dropdown for:", nextElement);
                    $(nextElement).select2("open");
                } else {
                    console.error("Select2 not initialized on the next element:", nextElement);
                }
            } else {
                console.log("Focusing on next element:", nextElement);
                nextElement.focus();
            }
        }
    }

    function initializeSelect2() {

        $(".add-select2").each(function () {
            if (!$(this).data("select2")) {
                console.log("Initializing Select2 for element:", this);
                $(this).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                });
            }
        });

        $('.add-select2').on('change', function (e) {
            var selectedRegType = $('#company_reg_type option:selected').val();
            @this.set('form.companyRegistorType', selectedRegType);
        });
   
    $(".add-select2").on("select2:close", function (e) {
        const form = e.target.form;
        const focusableElements = Array.from(
            form.querySelectorAll(
                'input, textarea, select, [tabindex]:not([tabindex="-1"])'
            )
        );
        moveToNextElement(form, focusableElements, e.target);
    });

     
    }

    initializeInputNavigation();
    initializeSelect2();

    Livewire.hook("message.processed", () => {
        console.log("Livewire processed, reinitializing Select2.");
        initializeSelect2();
    });
    });
</script>
@endscript




