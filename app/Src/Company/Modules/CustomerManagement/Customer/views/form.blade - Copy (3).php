@php
    $labels = App\Utility\Enums\CustomerRegisterTypeEnum::label();
    $reminderLabels = App\Utility\Enums\AutoReminderTypeEnum::label();
@endphp

<div x-data="custgroup">
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <!-- <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{$title}}</h3>
            </div>
        </div> -->
        <form class="form"  method="post" wire:submit="save" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row mb-6">
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="name">{{ __('company.input.name')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="name"
                                       maxlength="30"
                                       name="name" wire:model.blur="form.name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.name')]) }}">
                                <x-panel::error name="form.name"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                   for="email">{{ __('company.input.email')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="email" id="email"
                                       maxlength="30"
                                       
                                       name="email" wire:model.blur="form.email"
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
                                <input type="number" id="contact_number"
                                       maxlength="10"
                                       name="contactNumber" wire:model.blur="form.contactNumber"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.contact_number')]) }}">
                                <x-panel::error name="form.contactNumber"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="whatsapp_number">{{ __('company.input.whatsapp_number')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="number" id="whatsapp_number"
                                       maxlength="10"
                                       name="whatsappNumber" wire:model.blur="form.whatsappNumber"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.whatsapp_number')]) }}">
                                <x-panel::error name="form.whatsappNumber"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                   for="address">{{ __('company.input.address')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="address"
                                    maxlength="300"
                                    name="address" wire:model.blur="form.address"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.address')]) }}">
                            </div>
                            <x-panel::error name="form.address"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="city">{{ __('company.input.city')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="city"
                                    maxlength="30"
                                    name="city" wire:model.blur="form.city"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.city')]) }}">
                            </div>
                            <x-panel::error name="form.city"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="state">{{ __('company.input.state')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="state"
                                    maxlength="30"
                                    name="state" wire:model.blur="form.state"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.state')]) }}">
                            </div>
                            <x-panel::error name="form.state"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="pincode">{{ __('company.input.pincode')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="number" id="pincode"
                                    maxlength="30"
                                    name="pincode" wire:model.blur="form.pincode"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.pincode')]) }}">
                            </div>
                            <x-panel::error name="form.pincode"/>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.customer_group')}}
                            <a href='#' wire:click.prevent="storeToSession" class="m-input-icon__icon m-input-icon__icon--right"> Add New</a></label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2" id="customer_group"  wire:model.blur="form.customerGroup"
                            >
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.customer_group')]) }}</option>
                                @foreach($customerGroups as $customerGroup)
                                    <option value="{{ $customerGroup->id }}">{{ $customerGroup->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <x-panel::error name="form.customerGroup"/>
                    </div>
                    {{--<div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.customer_reg_type')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model="form.customerRegisterType" id="customer_reg_type">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.customer_reg_type')]) }}</option>
                                @foreach(App\Utility\Enums\CustomerRegisterTypeEnum::cases() as $case)
                                    <option value="{{ $case->name }}">{{  $labels[$case->name] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.customerRegisterType"/>
                    </div>--}}
@if($company_type =='Regular')
                    <div class="col-md-2">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="gstNo">{{ __('company.input.gst_no')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="text" id="gst_no"
                                       name="gstNo" wire:model.blur="form.gstNo"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.gst_no')]) }}">
                            </div>
                        </div>
                    </div>
@endif
                    <div class="col-md-2">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="name">{{ __('company.input.auto_reminder')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model="form.autoReminder" id="auto_reminder">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.auto_reminder')]) }}</option>
                                @foreach(App\Utility\Enums\AutoReminderTypeEnum::cases() as $case)
                                    <option value="{{ $case->name }}">{{  $reminderLabels[$case->name] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.autoReminder"/>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="openingBalance">{{ __('company.input.opening_balance')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="number" id="opening_balance" maxlength="12"
                                       name="openingBalance" wire:model.blur="form.openingBalance"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.opening_balance')]) }}">
                                <x-panel::error name="form.openingBalance"/>
                            </div>
                        </div>
                    </div>
<!-- Price add section start -->
                    <div class="card-header border-0">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Add Price</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 " for="productId">{{ __('company.input.product')}}</label>
                            <div class="col-lg-12 fv-row" wire:ignore>
                            <select class="form-select form-select-lg add-select2"  id="productId"
                            >
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.product')]) }}</option>
                                <option value="all">All</option>
                                @foreach($product as $single_product)
                                    <option value="{{ $single_product->id }}">{{$single_product->product_id}} - {{$single_product->name}}</option>
                                @endforeach
                            </select>
                            <x-panel::error name="form.productId"/>
                            </div>
                        </div>
                    </div>
                    @if($form->productId === 'all')
    @foreach($product as $single_product)
    <div class="row">
    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 " for="productId">{{ __('company.input.product')}}</label>
                            <div class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  id="productId" wire:model="form.productId" wire:change="updateDefaultPrice($event.target.value)">
                                <option value="{{ $single_product->id }}">{{$single_product->product_id}} - {{$single_product->name}}</option>
                               
                            </select>
                            <x-panel::error name="form.productId"/>
                            </div>
                        </div>
                    </div>
        <div class="col-md-3">
            <div class="row">
                <label class="col-lg-12 col-form-label fw-semibold fs-6">{{ __('company.input.special_price')}}</label>
                <div class="col-lg-12 fv-row">
                    <input type="number" wire:model.defer="form.product_price_new.{{ $single_product->id }}"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.add_price')]) }}">
                    <x-panel::error name="form.product_price_new.{{ $single_product->id }}"/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <label class="col-lg-12 col-form-label fw-semibold fs-6">{{ __('company.input.default_price') }}</label>
                <div class="col-lg-12 fv-row">
                    <input type="number" wire:model="form.product_price.{{ $single_product->id }}"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.default_price')]) }}">
                    <x-panel::error name="form.product_price.{{ $single_product->id }}"/>
                </div>
            </div>
        </div>
                    </div>
    @endforeach
    @else
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 " for="product_price_new">{{ __('company.input.special_price')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="number" id="product_price_new"
                                        wire:model.blur="form.product_price_new"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.add_price')]) }}">
                                <x-panel::error name="form.product_price_new"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 " for="product_price">{{ __('company.input.default_price')}}</label>
                            <div class="col-lg-12 fv-row">
                                <input type="number" id="product_price"
                                       wire:model.blur="form.product_price"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.default_price')]) }}">
                                <x-panel::error name="form.product_price"/>
                            </div>
                        </div>
                    </div>
    @endif
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="addprduct"></label>
                        <div wire:ignore class="col-lg-12 fv-row">

                        <button type="button" wire:click="addProduct" class="btn btn-primary btn-rounded" style="margin-top:14px;"><i class="fa-solid fa-plus"></i> </button>
                        </div>

                    </div>
<!-- Price add section end -->
                </div>
                @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
                <div class="row mb-8">
                @foreach($selectedProducts as $selectedProduct)
                <div class="col-md-3 mb-3">
                <label class="col-lg-12 col-form-label fw-semibold fs-6">
                {{ $selectedProduct['name'] }}
                </lable>
                </div>

                <div class="col-md-3">
                <label class="col-lg-12 col-form-label fw-semibold fs-6">
                {{ $selectedProduct['product_price_new'] }}
                </lable>
                </div>

                <div class="col-md-3">
                <label class="col-lg-12 col-form-label fw-semibold fs-6">
                {{ $selectedProduct['product_price'] }}
                </lable>
                </div>

                <div class="col-md-3">
                </lable>
                <a style="cursor: pointer" wire:click="removeProduct({{ $selectedProduct['id'] }})" data-toggle="tooltip" data-placement="top" >
                                    <i class="fa-solid fa-trash icon text-danger"></i>
                                </a>
                                </lable>
                </div>
                @endforeach
                {{-- <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-4 gy-5 no-footer">
                             <thead>
                            <tr class="text-start text-muted fw-bold fs-4 text-uppercase gs-0">
                            </tr>
                            </thead>

                        <tbody class="text-gray-800">

                                @foreach($selectedProducts as $selectedProduct)
                                    <tr>
                                    <td>{{ $selectedProduct['name'] }}</td>
                                    <td>{{ $selectedProduct['product_price_new'] }}</td>
                                    <td>{{ $selectedProduct['product_price'] }}</td>
                                    <td>

                                <a style="cursor: pointer" wire:click="removeProduct({{ $selectedProduct['id'] }})" data-toggle="tooltip" data-placement="top" >
                                    <i class="fa-solid fa-trash icon text-danger"></i>
                                </a>

                                    </td>
                                </tr>
                                @endforeach

                        </tbody>
                     </table>

                </div> --}}
            </div>
            <!-- <div x-show="loading" class="spinner-overlay">
        <div class="spinner"></div>
    </div> -->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.customer-management.customer.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
                   <button type="submit" class="btn btn-primary">
            {{ __('app.panel.submit') }}
           
        </button>
                <!-- <button type="submit" class="btn btn-primary">{{__('app.panel.submit')}}</button> -->
            </div>
        </form>
    </div>
</div>
@script
<script>

 Alpine.data('custgroup', () => {

    
    $(document).ready(function () {
        $('.add-select2').select2();
        $('.add-select2 option:first-child').prop('disabled', true);

        $('#customer_group').on('change', function () {
        var selectedCustomerGroup = $(this).val();
        @this.set('form.customerGroup', selectedCustomerGroup);
        });

        $('#customer_reg_type').on('change', function () {
        var selectedCustomerRegType = $(this).val();
        @this.set('form.customerRegisterType', selectedCustomerRegType);
        });
       
        $('#auto_reminder').on('change', function () {
        var selectedAutoReminder = $(this).val();
        @this.set('form.autoReminder', selectedAutoReminder);
        });
        



    });
    

    $('#productId').select2({
    placeholder: "{{ __('company.placeholder.select', ['name' => __('company.input.product')]) }}",
    allowClear: true
}).on('change', function () {
    let productId = $(this).val();
    console.log("Emitting event with productId:", productId); // Debugging log
    Livewire.emit('productChanged', productId); // Correct syntax
});



});
</script>

@endscript

<style>
.spinner-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.spinner {
    border: 5px solid rgba(0, 0, 0, 0.1);
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
