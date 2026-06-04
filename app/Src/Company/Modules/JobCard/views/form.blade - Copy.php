<div x-data="jobcard">
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <!-- <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{$title}}</h3>oip            </div>
        </div> -->
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row mb-6">
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="job_date">{{ __('company.input.date')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="date" id="job_date"  wire:model.blur="form.job_date" 
                           
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.job_date')]) }}">
                            <x-panel::error name="form.job_date"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="customer_id">{{ __('company.input.customer')}}

                        <a href='#' wire:click.prevent="storeToSession" style='margin-left: 159px'> Add New</a>
                        </label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model.blur="form.customer_id" id="customer_id">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.customer')]) }}</option>
                                @foreach($customer as $single_customer)
                                    <option value="{{ $single_customer->id }}">{{$single_customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.customer_id"/>
                    </div>
                    <div class="col-md-4">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="product_id">{{ __('company.input.product')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model.blur="form.product_id" id="product_id"
                            wire:change="updateProductData($event.target.value)"
                             >
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.product')]) }}</option>
                                @foreach($product as $single_product)
                                    <option value="{{ $single_product->id }}">{{$single_product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.product_id"/>
                    </div>
                   
                    <div class="col-md-1" >
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="is_inch">Is Inch</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="form.is_inch" value="1"
                                wire:click="$set('form.is_inch', $event.target.checked ? 1 : 0)" id="is_inch" x-model="isInchChecked" />
                                <label class="form-input-label" for="is_inch"><strong>Is Inch</strong></label>
                            </div>
                        </div> 
                    </div>  

                     
                    </div>
                    @if($selectedProduct)
                    <hr>

                <div class="row mb-6" >
                
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 no-footer">
                        <thead>
                            <tr class="col-lg-12 col-form-label fw-semibold fs-6">
                                <th>Product Name</th>
                                <th>Width</th>
                                <th>Height</th>
                                <th>Qty</th>
                                <th>Sq.ft</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <tr>
                                <td id='product_name'>
                                <strong>{{ $form->product_name }}</strong>
                                </td>
                                <td>
                                <input type="number" id="width"
                                       wire:model.blur="form.width"
                                       x-model="width"
                                     x-on:blur="convertToFeet('width')"
                                       class="form-control form-control-lg form-control-solid"
                                        oninput="this.value = this.value.replace(/^0+/, '')" 
                                       
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.width')]) }}">
                                <x-panel::error name="form.width"/>
                                </td>
                                <td>

                                
                                <input type="number" id="height"
                                       wire:model.blur="form.height"
                                      x-model="height"
                                     x-on:blur="convertToFeet('height')"
                                       class="form-control form-control-lg form-control-solid"
                                       oninput="this.value = this.value.replace(/^0+/, '')" 
                                        onblur="this.value = this.value ? parseInt(this.value, 10) : 0"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.height')]) }}">
                                <x-panel::error name="form.height"/>
                                </td>

                                <td>
                                <input type="number" id="qty"
                                       wire:model.blur="form.qty"
                                       class="form-control form-control-lg form-control-solid" 
                                       oninput="this.value = this.value.replace(/^0+/, '')" 
                                        onblur="this.value = this.value ? parseInt(this.value, 10) : 0"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.qty')]) }}">
                                <x-panel::error name="form.qty"/>
                                </td>
                                <td>
                                <input type="number" id="sq_ft"
                                       wire:model.blur="form.sq_ft"
                                       class="form-control form-control-lg form-control-solid" readonly
                                        oninput="this.value = this.value.replace(/^0+/, '')" 
                                        onblur="this.value = this.value ? parseInt(this.value, 10) : 0"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.sq_ft')]) }}">
                                <x-panel::error name="form.sq_ft"/>
                                </td>
                                <td>
                                <input type="number" id="rate"
                                       wire:model.blur="form.rate"
                                       class="form-control form-control-lg form-control-solid" readonly
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.rate')]) }}">
                                <x-panel::error name="form.rate"/>
                                </td>
                                <td>
                                <input type="number" id="amount" readonly
                                       wire:model.blur="form.amount"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.amount')]) }}">
                                <x-panel::error name="form.amount"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            <hr>
            <div class="row mb-12">
             <div class="col-md-6">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="description">{{ __('company.input.narration')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="description" maxlength="30" name="description" wire:model.blur="form.description"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.narration')]) }}">
                            <x-panel::error name="form.description"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 " for="image">{{ __('company.input.image')}}</label>
                        <div class="col-lg-12 fv-row">

                      
                        
                           <input type="file" id="jobImage" wire:model="form.jobImage"
                                    class="form-control form-control-lg form-control-solid filepond"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.jobImage')]) }}">
                            <x-panel::error name="form.jobImage"/>
                        </div>
                       
                    </div>
                     <div class="col-md-3">
                    @if($existingLogo)
                    <div class="mt-3">
                    <a href="{{ $existingLogo }}" target="_blank"><img src="{{ $existingLogo }}" alt="jobcard Logo" style="max-width: 100px; height: 100px;"></a>
                    </div>
                @endif
            </div>
            </div> 


            <!-- <input type="hidden" id="job_status" wire:model.blur="form.job_status" class="form-control form-control-lg form-control-solid"> -->
    <div class="row mb-6">
    <div class="col-md-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="fittingId" x-model="isFittingChecked" :checked="isFittingChecked" />
            <label class="form-input-label" for="fittingId"><strong>Fitting</strong></label>
        </div>
    </div>
    <div class="col-md-1" >
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pestingId" x-model="isPestingChecked" />
            <label class="form-input-label" for="pestingId"><strong>Pesting</strong></label>
        </div>
    </div>
    {{--<div class="col-md-1" >
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="framingId" x-model="isFramingChecked" />
            <label class="form-input-label" for="framingId"><strong>Framing</strong></label>
        </div>
    </div>--}}
    <div class="col-md-1" >
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="transportationId" x-model="isTransportationChecked" />
            <label class="form-input-label" for="transportationId"><strong>Transportation</strong></label>
        </div>
    </div>
</div>
                        @if(($isEdit))
                        <div class="row">
                                <div class="col-md-6 ps-0">
                                    <div class="card p-0 mt-3">
                                        <div class="card-body p-3 border rounded">
                                            <span class="fw-bold">Wastage: @if ($wastage_jobs->isNotEmpty())
                        @if ($wastage_jobs->first()->damage_type === 'whole_product')
                            Whole Product
                        @elseif ($wastage_jobs->first()->damage_type === 'materials')
                            Materials
                        @endif
                    @else
                        N/A
                    @endif </span>
                    <hr class="w-25 mt-1">
                    @if ($wastage_jobs->isNotEmpty() && $wastage_jobs->first()->damage_type === 'materials')
                    <p class="p-0 m-0">
                        <strong>{{ implode(' + ', $materials) }}</strong>
                    </p>
                @endif
                                            
                                        </div>
                                    </div>
                                </div>
                        </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="job_status">{{ __('company.input.status')}}</label>
                                                <div wire:ignore class="col-lg-12 fv-row">
                                                    <select class="form-select form-select-lg add-select2"  wire:model="form.job_status" id="job_status"
                                                   
                                                    >
                                                        <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.status')]) }}</option>
                                                        @foreach($joballstatus as $status)
                                                            <option value="{{ $status->value }}">{{$status->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <x-panel::error name="form.job_status"/>
                        </div>
                    </div> 
@endif
    <!-- </div> -->
    <div class="row">
    <div class="col-md-6 ps-0">
        <!-- You can add any content here if needed -->
    </div>
    <div class="col-md-4 ps-0">
        <!-- You can add any content here if needed -->
    </div>
    <div class="col-md-2">
        <div class="card p-0 mt-3 h-100">
            <div class="card-body p-3 border rounded">
                <!-- Flex container for fields -->
                
    <!-- Amount Section -->
    <div class="row mb-2 align-items-center">
        <label for="total_amount" class="col-md-6 col-form-label text-md-end"><strong>Amount:</strong></label>
        <div class="col-md-6">
            <input type="number" id="total_amount" wire:model="form.total_amount"
                class="form-control form-control-sm form-control-solid"
                placeholder="Enter Amount"
                oninput="this.value = this.value.replace(/^0+/, '')"
                onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
            <x-panel::error name="form.total_amount" />
        </div>
    </div>

    <!-- Fitting Section -->
    <template x-if="isFittingChecked">
        <div class="row mb-2 align-items-center">
            <label for="fitting_charge" class="col-md-6 col-form-label text-md-end"><strong>Fitting:</strong></label>
            <div class="col-md-6">
                <input type="text" id="fitting_charge" wire:model="form.fitting_charge"
                    class="form-control form-control-sm form-control-solid"
                    oninput="this.value = this.value.replace(/^0+/, '')"
                    onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
            </div>
        </div>
    </template>

    <!-- Pesting Section -->
    <template x-if="isPestingChecked">
        <div class="row mb-2 align-items-center">
            <label for="pesting_charge" class="col-md-6 col-form-label text-md-end"><strong>Pesting:</strong></label>
            <div class="col-md-6">
                <input type="text" id="pesting_charge" wire:model="form.pesting_charge"
                    class="form-control form-control-sm form-control-solid"
                    oninput="this.value = this.value.replace(/^0+/, '')"
                    onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
            </div>
        </div>
    </template>

    <!-- Framing Section -->
   {{-- <template x-if="isFramingChecked">
        <div class="row mb-2 align-items-center">
            <label for="framing_charge" class="col-md-3 col-form-label text-md-end"><strong>Framing:</strong></label>
            <div class="col-md-4">
                <input type="text" id="framing_charge" wire:model="form.framing_charge"
                    class="form-control form-control-sm form-control-solid"
                    oninput="this.value = this.value.replace(/^0+/, '')"
                    onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
            </div>
        </div>
    </template>--}}

    <!-- Transportation Section -->
    <template x-if="isTransportationChecked">
        <div class="row mb-2 align-items-center">
            <label for="transportation_charge" class="col-md-6 col-form-label text-md-end"><strong>Transportation:</strong></label>
            <div class="col-md-6">
                <input type="text" id="transportation_charge" wire:model="form.transportation_charge"
                    class="form-control form-control-sm form-control-solid"
                    oninput="this.value = this.value.replace(/^0+/, '')"
                    onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
            </div>
        </div>
    </template>

                <!-- Estimated Amount -->
                <hr class="w-100 mt-1">
                <span class="fw-bold">Estimated Amount: Rs <span x-text="estimateCount">0</span></span>
            </div>
        </div>
    </div>
</div>
            
</br>
@if($selectedCustomer && (!$isEdit))
            <div class="col-md-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 no-footer">
                            <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th>Sr.no</th>
                                                <th>Job Id.</th>
                                                <th>Width</th>
                                                <th>Height</th>
                                                <th>Qty</th>
                                                <th>Sq.ft</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                        </tr>
                    </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        @forelse($selectedCustomer as $index => $jobCard)
                                    <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $jobCard->job_no }}</td>
                                    <td>{{ $jobCard->width }}</td>
                                    <td>{{ $jobCard->height }}</td>
                                    <td>{{ $jobCard->qty }}</td>
                                    <td>{{ $jobCard->sq_ft }}</td>
                                    <td>₹{{ $jobCard->final_total }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center action-div">
                                        <a style="cursor: pointer" href="{{route('company.jobcard.edit',$jobCard->id)}}" data-toggle="tooltip" data-placement="top" title="Edit Jobcard Details">
                                                <i class="fa-solid fa-pen icon edit-icon"></i>
                                            </a>
                                    <a style="cursor: pointer" wire:confirm="Are you sure you want delete this" wire:click="delete({{ $jobCard->id }})" data-toggle="tooltip" data-placement="top" title="Delete Fee Details">
                                        <i class="fa-solid fa-trash icon text-danger"></i>
                                    </a>
                                    {{-- <a style="cursor: pointer" href="route('company.jobcard.edit',$jobCard->id)" data-toggle="tooltip" data-placement="top" title="View Jobcard Details">
                                             <i class="fa-solid fa-eye icon edit-icon"></i>
                                    </a>--}}
                                           
                                    </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Job Cards Found</td>
                                </tr>
                            @endforelse                       
                      
                       
                        </tbody>
                    </table>
    
                </div>

                   
                </div>
                @endif
            </div>
        </div>
        <input type="hidden" id="button_status" wire:model="form.button_status" class="form-control form-control-lg form-control-solid">
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.jobcard.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
                   @if(!$isEdit)
                <button type="submit" wire:click="$set('form.button_status', 'create')" class="btn btn-primary me-2">{{__('app.panel.createjob')}}</button>
                <button type="submit" wire:click="$set('form.button_status', 'finish')" class="btn btn-primary">{{__('app.panel.createjobfinish')}}</button>
          @else
          <button type="submit" wire:click="$set('form.button_status', 'finish')" class="btn btn-primary">{{__('app.panel.submit')}}</button>
                @endif
           
            </div>
        </form>
    </div>
</div>
@script
<script>
Alpine.data('jobcard', () => {


        return {
            total_amount: 0,
            fitting_charge: {{ $form->fitting_charge ?? 0 }},
            pesting_charge: {{ $form->pesting_charge ?? 0 }},
            framing_charge: {{ $form->framing_charge ?? 0 }},
            transportation_charge: {{ $form->transportation_charge ?? 0 }},
            estimateCount: 0,
            isFittingChecked: {{ $form->fitting_charge > 0 ? 'true' : 'false' }},
            isPestingChecked: {{ $form->pesting_charge > 0 ? 'true' : 'false' }},
            isFramingChecked: {{ $form->framing_charge > 0 ? 'true' : 'false' }},
            isTransportationChecked: {{ $form->transportation_charge > 0 ? 'true' : 'false' }},

            isInchChecked: false,
            width:0,
            height: 0,
            // width: @entangle('form.width').defer,
            // height: @entangle('form.height').defer,
           
            init() {
               
                this.$watch('$wire.form.total_amount', value => {
                    this.total_amount = parseFloat(value) || 0; 
                    this.calculateEstimate();
                });

                this.$watch('$wire.form.fitting_charge', value => {
                    this.fitting_charge = parseFloat(value) || 0; 
                    this.calculateEstimate();
                });
                this.$watch('$wire.form.pesting_charge', value => {
                    this.pesting_charge = parseFloat(value) || 0; 
                    this.calculateEstimate();
                });
                this.$watch('$wire.form.framing_charge', value => {
                    this.framing_charge = parseFloat(value) || 0; 
                    this.calculateEstimate();
                });
                this.$watch('$wire.form.transportation_charge', value => {
                    this.transportation_charge = parseFloat(value) || 0; 
                    this.calculateEstimate();
                });

                this.$watch('isFittingChecked', value => {
                if (!value) {
                    this.fitting_charge = 0;
                    this.$wire.set('form.fitting_charge', 0);
                }
            });

                 this.$watch('isPestingChecked', value => {
                if (!value) {
                    this.pesting_charge = 0; 
                    this.$wire.set('form.pesting_charge', 0); 
                }
                 });
                 this.$watch('isFramingChecked', value => {
                if (!value) {
                    this.framing_charge = 0; 
                    this.$wire.set('form.framing_charge', 0); 
                }
                 });
                 this.$watch('isTransportationChecked', value => {
                if (!value) {
                    this.transportation_charge = 0; 
                    this.$wire.set('form.transportation_charge', 0); 
                }
                 });


                 window.addEventListener('resetAlpineState', () => {
                this.isFittingChecked = false;
                this.isPestingChecked = false;
                this.isFramingChecked = false;
                this.isTransportationChecked = false;
                this.fitting_charge = 0;
                this.pesting_charge = 0;
                this.framing_charge = 0;
                this.transportation_charge = 0;
                this.calculateEstimate();
            });


            },
            convertToFeet(field) {
            if (this.isInchChecked) {
                if (field === 'width' && this.width > 0) {
                    this.width = Math.round(this.width / 12);
                    this.$wire.set('form.width', this.width);
                }
                if (field === 'height' && this.height > 0) {
                    this.height = Math.round(this.height / 12);
                    this.$wire.set('form.height', this.height);
                }
            }
        },

            calculateEstimate() {
               
                this.estimateCount = this.total_amount + this.fitting_charge + this.pesting_charge + this.framing_charge + this.transportation_charge;
            }
        };
    });


    // $(document).ready(function () {


    //     function initializeInputNavigation() 
    //     {
    //         document.addEventListener("keydown", (event) => {
    //          if (event.key === "Enter") 
    //             {
    //                 const form = event.target.form;
    //                 if (form) 
    //                 {
    //                     event.preventDefault();
    //                     const focusableElements = Array.from(
    //                         form.querySelectorAll(
    //                         'input, textarea, select, [tabindex]:not([tabindex="-1"])'
    //                         )
    //                     );
    //                     const currentIndex = focusableElements.indexOf(event.target);
    //                     if (currentIndex === focusableElements.length - 1) {
    //                         form.requestSubmit(); 
    //                     } else {
    //                     const nextElement = focusableElements[currentIndex + 1];
    //                         nextElement.focus();
    //                     }
    //                 }
    //             }
    //          });

    //     } 
        
        
    //     function initializeSelect2() 
    //     {

    //         $('.add-select2').select2();
    //         $('.add-select2 option:first-child').prop('disabled', true);
    //         $('.add-select2').on('change', function (e) 
    //         {
    //             var selectedCustomerId = $('#customer_id option:selected').val();
    //             var selectedProductId = $('#product_id option:selected').val();
    //             var selectedJobstatus = $('#job_status option:selected').val();
            
    //             @this.set('form.customer_id', selectedCustomerId);
    //             @this.set('form.product_id', selectedProductId);
    //             @this.set('form.job_status', selectedJobstatus);
            
    //             @this.call('updateProductData', selectedProductId);
    //             @this.call('updateCustomerData', selectedCustomerId);
    //         });

    //     }

        
    //             var preSelectedCustomerId = $('#customer_id option:selected').val();
    //             var preSelectedProductId = $('#product_id option:selected').val();
    //             console.log(preSelectedProductId);

    //             if (preSelectedCustomerId) {
    //                     @this.call('updateCustomerData', preSelectedCustomerId);
    //                  }
                    
    //             if (preSelectedProductId) {
    //                  @this.call('updateProductData', preSelectedProductId);
    //                  }

    //                  initializeInputNavigation();
    //                  initializeSelect2();
                     
    //                  Livewire.hook('message.processed', () => {
    //         initializeInputNavigation();
    //         initializeSelect2();
    //     });
    // });


    $(document).ready(function () {
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
                    ).filter(
                        (el) => !el.disabled && el.offsetParent !== null
                    ); // Filter visible and enabled elements

                    // Handle Select2 navigation
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
        // Trigger the first button's action dynamically
        const submitButton = form.querySelector('button[type="submit"][wire\\:click]');
        if (submitButton) {
            submitButton.click(); // Simulate a click on the submit button
        } else {
            console.error("No submit button found in the form.");
        }
    } else {
        let nextElement = focusableElements[currentIndex + 1];

        // Skip Select2 wrapper and move to the actual select element
        if ($(nextElement).hasClass("select2-selection")) {
            nextElement = focusableElements[currentIndex + 2] || nextElement;
        }

        // Focus on the next element or open Select2 dropdown
        if ($(nextElement).hasClass("add-select2")) {
            if ($.fn.select2 && $(nextElement).data("select2")) {
                $(nextElement).select2("open");
            } else {
                console.error("Select2 not initialized:", nextElement);
            }
        } else {
            nextElement.focus();
        }
    }
}

    function initializeSelect2() {
        $(".add-select2").each(function () {
            if (!$(this).data("select2")) {
                $(this).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                });
            }
        });

        $(".add-select2").on("select2:close", function (e) {
            const form = e.target.form;
            const focusableElements = Array.from(
                form.querySelectorAll(
                    'input, textarea, select, [tabindex]:not([tabindex="-1"])'
                )
            ).filter(
                (el) => !el.disabled && el.offsetParent !== null
            );

            const selectedProductId = $("#product_id option:selected").val();
            const selectedCustomerId = $("#customer_id option:selected").val();
            const selectedJobStatus = $("#job_status option:selected").val();

            @this.set("form.product_id", selectedProductId);
            @this.set("form.customer_id", selectedCustomerId);
            @this.set("form.job_status", selectedJobStatus);

            @this.call("updateProductData", selectedProductId);
            @this.call("updateCustomerData", selectedCustomerId);

            moveToNextElement(form, focusableElements, e.target);
        });
    }

    function preSelectValues() {
        const preSelectedCustomerId = $("#customer_id option:selected").val();
        const preSelectedProductId = $("#product_id option:selected").val();

        if (preSelectedCustomerId) {
            @this.call("updateCustomerData", preSelectedCustomerId);
        }

        if (preSelectedProductId) {
            @this.call("updateProductData", preSelectedProductId);
        }
    }

    initializeInputNavigation();
    initializeSelect2();
    preSelectValues();

    Livewire.hook("message.processed", () => {
        initializeSelect2();
    });
});
</script>
@endscript
