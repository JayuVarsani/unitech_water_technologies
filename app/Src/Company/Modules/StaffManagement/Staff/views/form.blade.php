<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <!-- <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{$title}}</h3>
            </div>
        </div> -->
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row">
                    <div class="d-flex justify-content-end">
                    <span class="{{ $form->status ? 'text-success' : 'text-danger' }} me-2">
                         {{ $form->status ? 'Active' : 'Inactive' }}
                        </span>
                        <label class="form-check form-switch form-check-custom status_switch">
                        <input class="form-check-input form-check-button  w-45px h-25px" name="form[status]" 
                            {{ $form->status ? 'checked' : '' }}
                            wire:model="form.status" type="checkbox">
                        </label>
                        </div>
                </div>

                <div class="row mb-6">

                    
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="staff_image">{{ __('admin.input.staff_image')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="file" id="staff_image"
                                       accept="image/*"
                                       name="form[staff_image]" wire:model.blur="form.staff_image"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('admin.placeholder.enter', ['name' => __('admin.input.staff_image')]) }}">
                                <x-panel::error name="form.staff_image"/>
                            </div>
                        </div>
                         @if($existingLogo )
                    <div class="mt-3">
                        <img src="{{ $existingLogo }}" alt="Company Logo" style="max-width: 100px; height: auto;">
                    </div>
                @endif
                    </div>
                   
                

                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="staff_name">{{ __('company.input.name')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="staff_name"
                                       maxlength="30"
                                       name="form[name]" wire:model.blur="form.name"
                                       onblur="this.value = this.value.trim()"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.name')]) }}">
                                <x-panel::error name="form.name"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 "
                                   for="staff_email">{{ __('company.input.email')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="staff_email"
                                       maxlength="30"
                                       name="form[email]" wire:model.blur="form.email"
                                       onblur="this.value = this.value.trim()"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.email')]) }}">
                                <x-panel::error name="form.email"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="contact_number">{{ __('company.input.contact_number')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="tel" id="contact_number"
                                       
                                       autocomplete="off"
                                       name="form[contactNumber]" wire:model.blur="form.contactNumber"
                                       {{-- oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" --}}
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.contact_number')]) }}">
                                <x-panel::error name="form.contactNumber"/>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="row mb-6">
                     <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="assign_role">{{ __('company.input.assign_role')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2" id="assign_role" name="form[assignRole]" wire:model="form.assignRole">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.assign_role')]) }}</option>
                                @foreach($assignRoles as $assignRole)
                                    <option value="{{ $assignRole->id }}">{{ $assignRole->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.assignRole"/>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                   for="address">{{ __('company.input.address')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="address"
                                    maxlength="30"
                                    name="form[address]" wire:model.blur="form.address"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.address')]) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="city">{{ __('company.input.city')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="city"
                                    maxlength="30"
                                    name="form[city]" wire:model.blur="form.city"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.city')]) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="state">{{ __('company.input.state')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="state"
                                    maxlength="30"
                                    name="form[state]" wire:model.blur="form.state"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.state')]) }}">
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="row mb-6">
                     <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="pincode">{{ __('company.input.pincode')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="number" id="pincode"
                                    maxlength="30"
                                    name="form[pincode]" wire:model.blur="form.pincode"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.pincode')]) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="staff_role">{{ __('company.input.staff_role')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2" id="staff_role" name="form[staffRole]"  wire:model.blur="form.staffRole">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.staff_role')]) }}</option>
                                @foreach($staffRoles as $staffRole)
                                    <option value="{{ $staffRole->id }}">{{ $staffRole->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.staffRole"/>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="joining_date">{{ __('company.input.joining_date')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="date" id="joining_date"
                                    
                                       name="form[joiningDate]" wire:model.blur="form.joiningDate"
                                        
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.joining_date')]) }}">
                                <x-panel::error name="form.joiningDate"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                   for="current_salary">{{ __('company.input.current_salary')}} (₹)</label>
                            <div class="col-lg-12 fv-row">
                                <input type="tel" id="current_salary"
                                       onblur="this.value = this.value.trim()"
                                       autocomplete="off"
                                       name="form[currentSalary]" wire:model.blur="form.currentSalary"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.current_salary')]) }}">
                                <x-panel::error name="form.currentSalary"/>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row mb-6" >
                   {{-- <div class="col-md-3" x-show="!$wire.form.status" >
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 "
                                for="last_working_day">{{ __('company.input.last_working_day')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="date" id="last_working_day"
                                  max="1979-12-31"
                                    name="lastWorkingDay" wire:model.blur="form.lastWorkingDay"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.joining_date')]) }}">
                                <x-panel::error name="form.lastWorkingDay"/>
                            </div>
                        </div>
                    </div>--}}
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 @if($form->id == 0) required @endif"
                                   for="password">{{ __('company.input.password')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="password"
                                       maxlength="30"
                                       name="form[password]" wire:model.blur="form.password"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.password')]) }}">
                                <x-panel::error name="form.password"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                   for="staff_document">{{ __('company.input.staff_document')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="file" id="staff_document"
                                       maxlength="30"
                                       name="form[staffDocument]" wire:model.blur="form.staffDocument"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.staff_document')]) }}">
                                <x-panel::error name="form.staffDocument"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 "
                                   for="account_number">{{ __('company.input.account_number')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="account_number"
                                       maxlength="30"
                                       name="form[accountNumber]" wire:model.blur="form.accountNumber"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.account_number')]) }}">
                                <x-panel::error name="form.accountNumber"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 "
                                   for="ifsc_code">{{ __('company.input.ifsc_code')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="ifsc_code"
                                       maxlength="30"
                                       name="form[ifscCode]" wire:model.blur="form.ifscCode"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.ifsc_code')]) }}">
                                <x-panel::error name="form.ifscCode"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.staff-management.staff.index') }}"
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

       

    $('#staff_role').on('change', function () {
        @this.set('form.staffRole', $(this).val());
    });

    $('#assign_role').on('change', function () {
       
        @this.set('form.assignRole', $(this).val());
    });

   


    });

   
    
</script>

<script>
    function convertDateFormat(input) {
        let date = new Date(input.value);
        let day = ('0' + date.getDate()).slice(-2);
        let month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are zero-based
        let year = date.getFullYear();
        input.value = day + '-' + month + '-' + year;
    }
</script>

@endscript