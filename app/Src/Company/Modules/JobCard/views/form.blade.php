<div x-data="jobcard">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save,delete" />
        @php
            $lastJobNo = \App\Models\JobCard::where('company_id', $company_id)->orderBy('id', 'desc')->value('job_no');

            $companyPrefix = 'A';

            if (preg_match('/(\d+)$/', $lastJobNo, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1; // Default if no previous job number exists
            }

            $nextJobNo = isset($jobcard->id) ? $jobcard->job_no : $companyPrefix . $nextNumber;
        @endphp


        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Job No -


                    <span class="copy-text" style="cursor: pointer;">
                        <span class="job-no-span" onclick="copyToClipboard(this)">{{ $nextJobNo }}</span>
                    </span>
            </div>
        </div>

        <style>
            b,
            strong {
                font-weight: 600;
            }


            #submitJobButton:focus,
            #createJobButton:focus,
            #finishJobButton:focus,
            #cancelJobButton:focus {
                border-color: red !important;
                box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.5);
            }
        </style>



        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row mb-6">
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                            for="job_date">{{ __('company.input.date') }}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="date" id="job_date" wire:model.blur="form.job_date"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.job_date')]) }}">
                            <x-panel::error name="form.job_date" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                            for="customer_id">{{ __('company.input.customer') }}
                            @if ($canCreateCustomer)
                                <a href='#' wire:click.prevent="storeToSession"
                                    class="m-input-icon__icon m-input-icon__icon--right" tabindex="-1"> Add New</a>
                            @endif
                        </label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2 form-control"
                                wire:model.defer="form.customer_id" id="customer_id">
                                <option value="">
                                    {{ __('company.placeholder.select', ['name' => __('company.input.customer')]) }}
                                </option>
                                @foreach ($customer as $single_customer)
                                    <option value="{{ $single_customer->id }}">{{ $single_customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.customer_id" />
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                            for="product_id">{{ __('company.input.product') }}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2" wire:model.blur="form.product_id"
                                id="product_id" wire:change="updateProductData($event.target.value)">
                                <option value="">
                                    {{ __('company.placeholder.select', ['name' => __('company.input.product')]) }}
                                </option>
                                @foreach ($product as $single_product)
                                    <option value="{{ $single_product->id }}">{{ $single_product->product_id }} -
                                        {{ $single_product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.product_id" />
                    </div>
                    @if (!$this->isSelectedProductPiece)
                        <div class="col-md-2">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6" for="is_inch">Measurement
                                Unit</label>
                            <div wire:ignore class="col-lg-12 fv-row">
                                <select class="form-select form-select-lg add-select2" wire:model.blur="form.is_inch"
                                    id="is_inch">
                                    <option value="">{{ __('company.placeholder.select', ['name' => 'inch']) }}
                                    </option>
                                    <option value="1">Inch</option>
                                    <option value="0">Feet</option>
                                </select>
                            </div>
                            <x-panel::error name="form.is_inch" />
                        </div>
                    @endif

                </div>
                @if ($selectedProduct)
                    <hr>

                    <div class="row mb-6" wire:transition>

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 no-footer">
                                <thead>
                                    <tr class="col-lg-12 col-form-label fw-semibold fs-6">
                                        <th>Product Name</th>
                                        @if (!$this->isSelectedProductPiece)
                                            <th>Width</th>
                                            <th>Height</th>
                                        @endif
                                        <th>Qty</th>
                                        @if (!$this->isSelectedProductPiece)
                                            <th>Sq.ft</th>
                                        @endif
                                        <th>Rate (₹)</th>
                                        <th>Amount (₹)</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-₹600 fw-semibold">
                                    <tr>
                                        <td id='product_name'>
                                            <strong>{{ $form->product_name }}</strong>
                                        </td>
                                        @if (!$this->isSelectedProductPiece)
                                            <td>
                                                <input type="number" id="width" step="0.01"
                                                    wire:model.blur="form.width" x-model="width"
                                                    x-on:change="convertToFeet('width')"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="0">
                                                <x-panel::error name="form.width" />
                                            </td>
                                            <td>


                                                <input type="number" id="height" step="0.01"
                                                    wire:model.blur="form.height" x-model="height"
                                                    x-on:change="convertToFeet('height')"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="0">
                                                <x-panel::error name="form.height" />
                                            </td>
                                        @endif

                                        <td>
                                            <input type="number" id="qty" wire:model.blur="form.qty"
                                                class="form-control form-control-lg form-control-solid"
                                                oninput="this.value = this.value.replace(/^0+/, '')"
                                                onblur="this.value = this.value ? parseInt(this.value, 10) : 0"
                                                placeholder="0">
                                            <x-panel::error name="form.qty" />
                                        </td>
                                        @if (!$this->isSelectedProductPiece)
                                            <td>
                                                <input type="number" id="sq_ft" step="0.01"
                                                    wire:model.blur="form.sq_ft"
                                                    class="form-control form-control-lg form-control-solid" readonly
                                                    placeholder="0">
                                                <x-panel::error name="form.sq_ft" />
                                            </td>
                                        @endif
                                        <td>
                                            <input type="number" id="rate" step="0.01"
                                                wire:model.blur="form.rate"
                                                class="form-control form-control-lg form-control-solid" readonly
                                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.rate')]) }}">
                                            <x-panel::error name="form.rate" />
                                        </td>
                                        <td>
                                            <input type="number" id="amount" step="0.01" readonly
                                                wire:model.blur="form.amount"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="0">
                                            <x-panel::error name="form.amount" />
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
                        <label class="col-lg-12 col-form-label fw-semibold fs-6"
                            for="description">{{ __('company.input.narration') }}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="description" name="description"
                                wire:model.blur="form.description"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.narration')]) }}">
                            <x-panel::error name="form.description" />
                        </div>
                    </div>
                    {{-- <div class="col-md-3" x-data="{ modelName: 'form.jobImage' }">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 "
                            for="image">{{ __('company.input.image') }}</label>
                        <div class="col-lg-12 fv-row">

                            <x-panel::form.image-capture key="jobImage" :form="$form" name="jobImage"
                                :prevImage="$form->oldJobImage" />

                            <x-panel::error name="form.jobImage" />
                        </div>

                    </div> --}}
                    <div class="col-md-3" x-data="{ modelName: 'form.jobImage' }">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 "
                            for="image">{{ __('company.input.image') }}</label>
                        <div class="col-lg-12 fv-row">

                            <x-panel::form.image-editor name="jobImage" :form="$form" :prevImage="$form->oldJobImage" />
                            <x-panel::error name="form.jobImage" />
                        </div>

                    </div>
                    {{-- <div class="col-md-3">
                    @if ($existingLogo)
                    <div class="mt-3">
                    <a href="{{ $existingLogo }}" target="_blank"><img src="{{ $existingLogo }}" alt="jobcard Logo" style="max-width: 100px; height: 100px;"></a>
                    </div>-
                      @endif
                    </div> --}}
                </div>


                <!-- <input type="hidden" id="job_status" wire:model.blur="form.job_status" class="form-control form-control-lg form-control-solid"> -->
                <div class="row mb-6">

                    <div class="col-md-2">
                        <div class="form-check" x-data="{ isChecked: false, isFocused: false }">
                            <input class="form-check-input" type="checkbox" id="pestingId"
                                x-model="isPestingChecked"
                                @keydown.enter.prevent="isPestingChecked = !isPestingChecked"
                                @focus="isFocused = true" @blur="isFocused = false"
                                x-bind:class="isFocused ? 'border border-primary' : 'border-gray-300'" />
                            <label class="form-input-label" for="pestingId"><strong>Pesting</strong></label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-check" x-data="{ isChecked: false, isFocused: false }">
                            <input class="form-check-input" type="checkbox" id="fittingId"
                                x-model="isFittingChecked"
                                @keydown.enter.prevent="isFittingChecked = !isFittingChecked"
                                @focus="isFocused = true" @blur="isFocused = false"
                                x-bind:class="isFocused ? 'border border-primary' : 'border-gray-300'" />
                            <label class="form-input-label" for="fittingId"><strong>Fitting</strong></label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-check" x-data="{ isChecked: false, isFocused: false }">
                            <input class="form-check-input" type="checkbox" id="transportationId"
                                x-model="isTransportationChecked"
                                @keydown.enter.prevent="isTransportationChecked = !isTransportationChecked"
                                @focus="isFocused = true" @blur="isFocused = false"
                                x-bind:class="isFocused ? 'border border-primary' : 'border-gray-300'" />
                            <label class="form-input-label"
                                for="transportationId"><strong>Transportation</strong></label>
                        </div>
                    </div>
                    {{-- <div class="col-md-1" >
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="framingId" x-model="isFramingChecked" />
                    <label class="form-input-label" for="framingId"><strong>Framing</strong></label>
                </div>
            </div> --}}

                    <div class="col-md-2 ps-0">
                        <!-- You can add any content here if needed -->
                    </div>
                    <div class="col-md-4">

                        <div class="card p-0 mt-3 h-100">
                            <div class="card-body p-3 border rounded">
                                <!-- Flex container for fields -->

                                <!-- Amount Section -->
                                <div class="row mb-2 align-items-center">
                                    <label for="total_amount"
                                        class="col-md-6 col-form-label text-md-end"><strong>Amount: ₹</strong> </label>
                                    <div class="col-md-6">
                                        <input type="number" id="total_amount" step="0.01"
                                            wire:model="form.total_amount"
                                            class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter Amount">
                                        <x-panel::error name="form.total_amount" />
                                    </div>
                                </div>

                                <template x-if="isPestingChecked">
                                    <div class="row mb-2 align-items-center">
                                        <label for="pesting_charge"
                                            class="col-md-6 col-form-label text-md-end"><strong>Pesting:
                                                ₹</strong></label>
                                        <div class="col-md-6">
                                            <input type="text" id="pesting_charge"
                                                wire:model="form.pesting_charge"
                                                class="form-control form-control-sm form-control-solid"
                                                oninput="this.value = this.value.replace(/^0+/, '')"
                                                onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
                                        </div>
                                    </div>
                                </template>
                                <template x-if="isFittingChecked">
                                    <div class="row mb-2 align-items-center">
                                        <label for="fitting_charge"
                                            class="col-md-6 col-form-label text-md-end"><strong>Fitting:
                                                ₹</strong></label>
                                        <div class="col-md-6">
                                            <input type="text" id="fitting_charge"
                                                wire:model="form.fitting_charge"
                                                class="form-control form-control-sm form-control-solid"
                                                oninput="this.value = this.value.replace(/^0+/, '')"
                                                onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
                                        </div>
                                    </div>
                                </template>

                                <!-- Transportation Section -->
                                <template x-if="isTransportationChecked">
                                    <div class="row mb-2 align-items-center">
                                        <label for="transportation_charge"
                                            class="col-md-6 col-form-label text-md-end "><strong>Transportation:
                                                ₹</strong></label>
                                        <div class="col-md-6">
                                            <input type="text" id="transportation_charge"
                                                wire:model="form.transportation_charge"
                                                class="form-control form-control-sm form-control-solid"
                                                oninput="this.value = this.value.replace(/^0+/, '')"
                                                onblur="this.value = this.value ? parseInt(this.value, 10) : 0">
                                        </div>
                                    </div>
                                </template>
                                <hr class="w-100 mt-1">
                                <span class="fw-bold ms-2">Estimated Amount: ₹ <span x-text="estimateCount"
                                        class=" ms-5">0</span></span>

                            </div>
                        </div>
                    </div>


                </div>


                @if ($isEdit)
                    <div class="row">
                        <div class="col-md-6 ps-0">
                            <div class="card p-0 mt-3">
                                @if ($wastage_jobs_whole->isNotEmpty())
                                    <div class="card-body p-3 border rounded">
                                        @foreach ($wastage_jobs_whole as $single_wastage)
                                            @if ($single_wastage->damage_type == 'whole_product')
                                                <span class="fw-bold"> Wastage: Whole Product</span>
                                            @elseif($single_wastage->damage_type == 'materials')
                                                <span class="fw-bold"> Wastage: Material</span>
                                                <br>
                                                <span class="fw-bold">{{ $single_wastage->material_name }} (H:
                                                    {{ $single_wastage->height }}({{ $single_wastage->unit_name }}),
                                                    W:
                                                    {{ $single_wastage->width }}({{ $single_wastage->unit_name }}))</span>
                                            @endif
                                            <br>
                                            <span class="fw-bold">Note: {{ $single_wastage->note }}</span>
                                            <hr class="w-100 mt-1">
                                        @endforeach


                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                for="job_status">{{ __('company.input.status') }}</label>
                            <div wire:ignore class="col-lg-12 fv-row">
                                <select class="form-select form-select-lg add-select2" wire:model="form.job_status"
                                    id="job_status">
                                    <option value="">
                                        {{ __('company.placeholder.select', ['name' => __('company.input.status')]) }}
                                    </option>
                                    @foreach ($joballstatus as $status)
                                        <option value="{{ $status->value }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-panel::error name="form.job_status" />
                        </div>
                    </div>
                @endif



                </br>
                @if ($selectedCustomer)
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
                                        <tbody class="text-black-600">

                                            @php
                                                $totalSum = 0; // Initialize total sum
                                            @endphp
                                            @forelse($selectedCustomer as $index => $jobCard)
                                                @php
                                                    $totalSum += $jobCard->final_total; // Add each row's total
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $jobCard->job_no }}</td>
                                                    <td>{{ $jobCard->width }}</td>
                                                    <td>{{ $jobCard->height }}</td>
                                                    <td>{{ $jobCard->qty }}</td>
                                                    <td>{{ $jobCard->sq_ft }}</td>
                                                    <td>₹{{ number_format($jobCard->final_total, 2) }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center action-div">
                                                            <a style="cursor: pointer"
                                                                href="{{ route('company.jobcard.edit', $jobCard->id) }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Edit Jobcard Details" tabindex="-1">
                                                                <i class="fa-solid fa-pen icon edit-icon"></i>
                                                            </a>
                                                            <a style="cursor: pointer"
                                                                wire:confirm="Are you sure you want delete this"
                                                                wire:click="delete({{ $jobCard->id }})"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Delete Jobcard">
                                                                <i class="fa-solid fa-trash icon text-danger"></i>
                                                            </a>
                                                            {{-- <a style="cursor: pointer" href="route('company.jobcard.edit',$jobCard->id)" data-toggle="tooltip" data-placement="top" title="View Jobcard Details">
                                             <i class="fa-solid fa-eye icon edit-icon"></i>
                                    </a> --}}

                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty

                                                <tr>
                                                    <td colspan="8" class="text-center">No Job Cards Found</td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <td colspan="6" class="text-end fw-bold">Total Amount:</td>
                                                <td class="fw-bold">₹{{ number_format($totalSum, 2) }}</td>
                                                <td></td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>


                            </div>
                @endif
            </div>
    </div>
    <input type="hidden" id="button_status" wire:model="form.button_status"
        class="form-control form-control-lg form-control-solid">
    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <a href="{{ route('company.jobcard.index') }}" class="btn btn-light btn-active-light-primary me-2"
            id="cancelJobButton">{{ __('app.panel.cancel') }}</a>
        @if (!$isEdit)
            <button type="submit" wire:click="$set('form. ', 'create')" class="btn btn-primary me-2"
                id="createJobButton">{{ __('app.panel.createjob') }}</button>
            <button type="submit" wire:click="$set('form.button_status', 'finish')" class="btn btn-primary me-2"
                id="finishJobButton">{{ __('app.panel.finishjob') }}</button>
        @else
            <button type="submit" wire:click="$set('form.button_status', 'create')" class="btn btn-primary"
                id="submitJobButton">{{ __('app.panel.submit') }}</button>
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

                is_inch: {{ $form->is_inch ?? 0 }},
                width: {{ $form->width ?? 0 }},
                height: {{ $form->height ?? 0 }},


                init() {



                    this.$watch('$wire.form.is_inch', value => {

                        this.is_inch = parseInt(value) || 0;

                    });




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
                        this.width = 0;
                        this.height = 0;
                        this.calculateEstimate();
                    });
                    window.addEventListener('scrollToTop', () => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });

                },
                convertToFeet(field) {
                    // Only convert when "In Inch" is Yes
                    if (parseInt(this.is_inch, 10) !== 1) return;

                    if (field === 'width' && this.width > 0) {
                        const feet = this.width / 12;
                        // When other dimension is integer: round UP to nearest 0.5 (e.g. 25" → 2.08 → 2.5)
                        this.width = this.isInteger(this.height) ?
                            Math.ceil((feet) * 2) / 2 :
                            Math.ceil(feet);
                        this.$wire.set('form.width', this.width);
                    }
                    if (field === 'height' && this.height > 0) {
                        const feet = this.height / 12;
                        this.height = this.isInteger(this.width) ?
                            Math.ceil((feet) * 2) / 2 :
                            Math.ceil(feet);
                        this.$wire.set('form.height', this.height);
                    }
                },
                isInteger(value) {
                    return Number.isInteger(parseFloat(value));
                },


                calculateEstimate() {

                    this.estimateCount = this.total_amount + this.fitting_charge + this.pesting_charge + this
                        .framing_charge + this.transportation_charge;
                }
            };
        });

        $(document).ready(function() {
            $('.add-select2').select2();
            //$('.add-select2 option:first-child').prop('disabled', true);

            $('#customer_id').on('change', function() {

                var selectedCustomerId = $(this).val();
                @this.set('form.customer_id', selectedCustomerId);
                @this.call("updateCustomerData", selectedCustomerId);
            });

            $('#product_id').on('change', function() {
                var selectedProductId = $(this).val();
                @this.set('form.product_id', selectedProductId);
                @this.call("updateProductData", selectedProductId);
            });

            $('#job_status').on('change', function() {
                var selectedJobStatus = $(this).val();
                @this.set('form.job_status', selectedJobStatus);
            });

            $('#is_inch').on('change', function() {
                var selectedInch = $(this).val();
                @this.set('form.is_inch', selectedInch);
            });



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

            preSelectValues();

            // Handle Enter key like Tab inside the form (prevent submit on Enter)
            // Use delegated event so it still works after Livewire re-renders the form
            $(document).on('keydown', 'form.form', function(event) {
                if (event.key === 'Enter' || event.keyCode === 13) {
                    // Allow Enter on submit buttons
                    if ($(event.target).is('button[type="submit"], input[type="submit"], button')) {
                        return true;
                    }
                    if ($(event.target).closest('button').length) {
                        return true;
                    }

                    event.preventDefault();
                    event.stopPropagation();

                    const $inputs = $(this)
                        .find(
                            'input:not([type="hidden"]):not([type="submit"]):not([readonly]), select, textarea'
                        )
                        .filter(function() {
                            return !this.disabled && $(this).is(':visible');
                        });

                    const idx = $inputs.index(event.target);
                    if (idx >= 0 && idx < $inputs.length - 1) {
                        $inputs.eq(idx + 1).focus();
                    } else if (idx >= 0) {
                        $inputs.eq(0).focus();
                    }

                    return false;
                }
            });
        });
    </script>
@endscript
