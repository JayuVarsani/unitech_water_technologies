<div x-data="inquiry">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save,delete,updateCustomerData,updateProductData,addJob,updateJob" />
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top px-6 py-2">
                <div class="row mb-6 justify-content-between">
                    <div class="col-md-2">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6"
                            for="inquiry_id">{{ __('company.inquiry_id') }}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" class="form-control form-control-lg form-control-solid"
                                value="{{ $inquiry->id ?? '-' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                            for="date">{{ __('company.input.date') }}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="date" id="date" wire:model.blur="form.date"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.date')]) }}">
                            <x-panel::error name="form.date" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                            for="customer_id">{{ __('company.input.customer') }}
                        </label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2 form-control border-on-focus"
                                wire:model.defer="form.customer_id" id="customer_id"
                                wire:change="updateCustomerData($event.target.value)">
                                <option value="">
                                    {{ __('company.placeholder.select', ['name' => __('company.input.customer')]) }}
                                </option>
                                @foreach ($customer as $single_customer)
                                    <option wire:key="customer-{{ $single_customer->id }}"
                                        value="{{ $single_customer->id }}">{{ $single_customer->name }}
                                        ({{ $single_customer->contact_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.customer_id" />
                    </div>
                    <div class="col-md-2">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                            for="status">{{ __('company.input.status') }}</label>
                        <div class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg form-control" wire:model.live="form.status"
                                id="status">
                                <option value="">
                                    {{ __('company.placeholder.select', ['name' => __('company.input.status')]) }}
                                </option>
                                @foreach ($this->getStatuses() as $single_status)
                                    <option wire:key="status-{{ $single_status->value }}"
                                        value="{{ $single_status->value }}">{{ $single_status->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.status" />
                    </div>
                </div>
                <hr class="text-muted">
                <div class="row card">
                    <div class="card-header bg-light-primary align-items-center">
                        <h5 class="card-title">{{ __('company.jobs.title') }}</h5>
                    </div>

                    <div class="row mb-2">

                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                        for="product_id">{{ __('company.input.product') }}</label>
                                    <div class="col-lg-12 fv-row" wire:ignore>
                                        <select class="form-select form-select-lg add-select2 border-on-focus"
                                            wire:model.blur="form.product_id" id="product_id"
                                            wire:change="updateProductData($event.target.value)">
                                            <option value="">
                                                {{ __('company.placeholder.select', ['name' => __('company.input.product')]) }}
                                            </option>
                                            @foreach ($product as $single_product)
                                                <option wire:key="product-{{ $single_product->id }}"
                                                    value="{{ $single_product->id }}">
                                                    {{ $single_product->product_id }} -
                                                    {{ $single_product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-panel::error name="form.product_id" />
                                </div>
                                @if (!$this->form->isSelectedProductPiece)
                                    <div class="col-md-4">
                                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                            for="measurement_unit">{{ __('company.input.measurement_unit') }}</label>
                                        <div class="col-lg-12 fv-row">
                                            <select class="form-select form-select-lg"
                                                wire:model.blur="form.measurement_unit" id="measurement_unit">
                                                <option value="">
                                                    {{ __('company.placeholder.select', ['name' => 'inch']) }}
                                                </option>
                                                <option value="inch">Inch</option>
                                                <option value="feet">Feet</option>
                                            </select>
                                        </div>
                                        <x-panel::error name="form.measurement_unit" />
                                    </div>
                                @endif
                            </div>
                            <div class="row g-3 gy-0">
                                @if (!$this->form->isSelectedProductPiece)
                                    <div class="col-12 col-sm-6 col-lg-2">
                                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                            for="width">{{ __('company.input.width') }}</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="number" id="width" name="width" min="0"
                                                {{-- @readonly(!isset($this->form->product_id)) wire:loading.attr="readonly" --}} wire:model.blur="form.width" x-model="width"
                                                x-on:change="convertToFeet('width')"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.width')]) }}">
                                        </div>
                                        <x-panel::error name="form.width" />
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-2">
                                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                            for="height">{{ __('company.input.height') }}</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="number" id="height" name="height" min="0"
                                                {{-- @readonly(!isset($this->form->product_id))  --}} wire:model.blur="form.height" x-model="height"
                                                x-on:change="convertToFeet('height')"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.height')]) }}">
                                        </div>
                                        <x-panel::error name="form.height" />
                                    </div>
                                @endif
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                        for="qty">{{ __('company.input.qty') }}</label>
                                    <div class="col-lg-12 fv-row">
                                        <input type="number" id="qty" name="qty" min="0"
                                            {{-- @readonly(!isset($this->form->product_id))  --}} wire:model.blur="form.qty"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.qty')]) }}">
                                    </div>
                                    <x-panel::error name="form.qty" />
                                </div>
                                @if (!$this->form->isSelectedProductPiece)
                                    <div class="col-12 col-sm-6 col-lg-2">
                                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                            for="sq_ft">{{ __('company.input.sq_ft') }}</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="text" id="sq_ft" name="sq_ft" min="0"
                                                readonly value="{{ $this->form->sq_ft ?? 0 }}"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.sq_ft')]) }}">
                                        </div>
                                        <x-panel::error name="form.sq_ft" />
                                    </div>
                                @endif
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                        for="rate">{{ __('company.input.rate') }}</label>
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" id="rate" name="rate" min="0" readonly
                                            value="{{ $this->form->rate ?? 0 }}"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.rate')]) }}">
                                    </div>
                                    <x-panel::error name="form.rate" />
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required"
                                        for="amount">{{ __('company.input.amount') }}</label>
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" id="amount" name="amount" min="0" readonly
                                            value="{{ $this->form->amount ?? 0 }}"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.amount')]) }}">
                                    </div>
                                    <x-panel::error name="form.amount" />
                                </div>
                                @if (!$this->form->isSelectedProductPiece)
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                            for="pesting_charge">{{ __('company.input.pesting_charge') }}</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="number" step="any" id="pesting_charge" min="0"
                                                name="pesting_charge" wire:model.blur="form.pesting_charge"
                                                class="form-control form-control-lg form-control-solid"
                                                autocomplete="off"
                                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.pesting_charge')]) }}">
                                        </div>
                                        <x-panel::error name="form.pesting_charge" />
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                            for="fitting_charge">{{ __('company.input.fitting_charge') }}</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="number" step="any" id="fitting_charge" min="0"
                                                name="fitting_charge" wire:model.blur="form.fitting_charge"
                                                class="form-control form-control-lg form-control-solid"
                                                autocomplete="off"
                                                placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.fitting_charge')]) }}">
                                        </div>
                                        <x-panel::error name="form.fitting_charge" />
                                    </div>
                                @endif
                                <div class="col-12 col-sm-6 col-lg-6">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                        for="narration">{{ __('company.input.narration') }}</label>
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" maxlength="255" id="narration" name="narration"
                                            wire:model.blur="form.narration"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.narration')]) }}">
                                    </div>
                                    <x-panel::error name="form.narration" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex flex-column gap-3">
                            <div class="row" x-data="{ modelName: 'form.jobImage' }">
                                <label class="col-lg-12 col-form-label fw-semibold fs-6"
                                    for="image">{{ __('company.input.image') }}</label>
                                <div class="col-lg-12 fv-row">

                                    <x-panel::form.inquiry-image-editor name="jobImage" :form="$form"
                                        :prevImage="$form->oldJobImage" :isEdit="$isEdit" />
                                    <x-panel::error name="form.jobImage" />
                                </div>
                            </div>
                            <div class="row mt-auto">
                                @if ($this->form->job_index != '')
                                    <button type="button" class="btn btn-primary"
                                        @click="$dispatch('validate-image-editor', { type: 'updateJob' });">
                                        <i class="fa-regular fa-pen-to-square"></i> {{ __('company.jobs.edit') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary"
                                        @click="$dispatch('validate-image-editor', { type: 'addJob' });">
                                        <i class="fa-solid fa-plus"></i> {{ __('company.jobs.add') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                </br>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light-primary align-items-center">
                            <h5 class="card-title">{{ __('company.jobs.list') }}</h5>

                        </div>
                        <div class="card-body p-4" x-data="alert">
                            <x-panel::table.main :items="$selectedJobs" :pagination="false">
                                <x-panel::table.head>
                                    <th>{{ __('company.input.jobno') }}</th>
                                    <th>{{ __('company.input.image') }}</th>
                                    <th>{{ __('company.input.product') }}</th>
                                    <th>{{ __('company.input.unit') }}</th>
                                    <th>{{ __('company.input.width') }}</th>
                                    <th>{{ __('company.input.height') }}</th>
                                    <th>{{ __('company.input.qty') }}</th>
                                    <th>{{ __('company.input.sq_ft') }}</th>
                                    <th>{{ __('company.input.rate') }}</th>
                                    <th>{{ __('company.input.pesting_charge') }}</th>
                                    <th>{{ __('company.input.fitting_charge') }}</th>
                                    {{-- <th>{{__('company.input.transportation_charge')}}</th> --}}
                                    {{-- <th>{{ __('company.input.narration') }}</th> --}}
                                    <th>{{ __('company.input.amount') }}</th>
                                    <th>{{ __('company.action') }}</th>
                                </x-panel::table.head>
                                <x-panel::table.body :items="$selectedJobs">
                                    @foreach ($selectedJobs as $index => $job)
                                        <tr wire:key="job-{{ $index }}">
                                            <td>
                                                {{ $job['job_no'] ?? '-' }}
                                            </td>
                                            <td>
                                                @if ($isEdit)
                                                    @if (!str_starts_with($job['image'], 'http'))
                                                        <a href="{{ $job['image']?->temporaryUrl() ?? asset('build/panel/images/thumbnail.jpg') }}"
                                                            target="_blank" data-fancybox>
                                                            <img src="{{ $job['image']?->temporaryUrl() ?? asset('build/panel/images/thumbnail.jpg') }}"
                                                                style="height: 25px;width:25px;" alt="Job Image" />
                                                        </a>
                                                    @else
                                                        <a href="{{ $job['image'] ?? asset('build/panel/images/thumbnail.jpg') }}"
                                                            target="_blank" data-fancybox>
                                                            <img src="{{ $job['image'] ?? asset('build/panel/images/thumbnail.jpg') }}"
                                                                style="height: 25px;width:25px;" alt="Job Image" />
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ $job['image']?->temporaryUrl() ?? asset('build/panel/images/thumbnail.jpg') }}"
                                                        target="_blank" data-fancybox>
                                                        <img src="{{ $job['image']?->temporaryUrl() ?? asset('build/panel/images/thumbnail.jpg') }}"
                                                            style="height: 25px;width:25px;" alt="Job Image" /></a>
                                                @endif
                                                <x-panel::error name="selectedJobs.{{ $index }}.image" />
                                            </td>
                                            <td>{{ $job['product_name'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.product_name" />
                                                <x-panel::error name="selectedJobs.{{ $index }}.product_id" />
                                            </td>
                                            <td>{{ $job['measurement_unit'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.measurement_unit" />
                                            </td>
                                            <td>{{ $job['width'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.width" /></td>
                                            <td>{{ $job['height'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.height" /></td>
                                            <td>{{ $job['qty'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.qty" /></td>
                                            <td>{{ $job['sq_ft'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.sq_ft" /></td>
                                            <td>{{ $job['rate'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.rate" /></td>
                                            <td>{{ $job['pesting_charge'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.pesting_charge" />
                                            </td>
                                            <td>{{ $job['fitting_charge'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.fitting_charge" />
                                            </td>
                                            {{-- <td>{{ $job['transportation_charge'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.transportation_charge" /></td> --}}
                                            {{-- <td class="text-center" title="{{ $job['narration'] ?? '-' }}">
                                                <i class="fa-solid fa-info-circle"></i>
                                                <x-panel::error name="selectedJobs.{{ $index }}.narration" />
                                            </td> --}}
                                            <td>{{ $job['amount'] ?? '-' }} <x-panel::error
                                                    name="selectedJobs.{{ $index }}.amount" /></td>
                                            <td>
                                                <div class="d-flex justify-content-center action-div">
                                                    <span title="{{ $job['narration'] }}">
                                                        @if ($job['narration'])
                                                            <span style="cursor: pointer"
                                                                x-on:click="showNarration('{{ $job['narration'] }}')"><i
                                                                    class="fa-solid fa-info-circle text-dark"></i></span>
                                                        @else
                                                            <span style="cursor: not-allowed"
                                                                x-on:click="showNarration('{{ $job['narration'] }}')"><i
                                                                    class="fa-solid fa-circle-info text-muted"></i></span>
                                                        @endif
                                                        <x-panel::error
                                                            name="selectedJobs.{{ $index }}.narration" />
                                                    </span>
                                                    <a style="cursor: pointer" data-toggle="tooltip"
                                                        wire:click="editJob('{{ $index }}')"
                                                        data-placement="top" title="Edit Job">
                                                        <i class="fa-solid fa-pencil icon text-primary"
                                                            wire:loading.remove
                                                            wire:target="editJob('{{ $index }}')"></i>
                                                        <x-panel::inline-loader
                                                            target="editJob('{{ $index }}')"
                                                            wire:target="editJob('{{ $index }}')" />
                                                    </a>
                                                    <a style="cursor: pointer" data-toggle="tooltip"
                                                        wire:click="deleteJob({{ $index }})"
                                                        data-placement="top" title="Delete Job">
                                                        <i class="fa-solid fa-trash icon text-danger"
                                                            wire:loading.remove
                                                            wire:target="deleteJob({{ $index }})"></i>
                                                        <x-panel::inline-loader
                                                            target="deleteJob({{ $index }})" text="danger"
                                                            wire:target="deleteJob({{ $index }})" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="9"></td>
                                        <td class="text-center"><span
                                                class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.total') }}
                                            </span>{{ $selectedJobs->sum('pesting_charge') ?? '-' }}</td>
                                        <td class="text-center"><span
                                                class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.total') }}
                                            </span>{{ $selectedJobs->sum('fitting_charge') ?? '-' }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </x-panel::table.body>
                                <tr>
                                    <td colspan="100%">
                                        <x-panel::error name="form.selectedJobs" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="align-top">
                                        <div class="d-flex flex-column gap-4">
                                            <div class="d-flex flex-column gap-2">
                                                <label class="col-form-label fw-semibold fs-6 text-start p-0"
                                                    for="description">{{ __('company.input.narration') }}</label>
                                                <div class="fv-row">
                                                    <textarea name="description" id="description" maxlength="255" rows="3" wire:model.blur="form.description"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.narration')]) }}"></textarea>
                                                    <x-panel::error name="form.description" />
                                                </div>
                                            </div>
                                            @if ($this->form->status == \App\Utility\Enums\InquiryStatusEnum::Cancelled->value)
                                                <div class="d-flex flex-column gap-2">
                                                    <label class="col-form-label fw-semibold fs-6 text-start p-0"
                                                        for="cancellation_reason">
                                                        {{ __('company.input.cancellation_reason') }}
                                                    </label>
                                                    <div class="fv-row">
                                                        <textarea name="cancellation_reason" id="cancellation_reason" maxlength="255" rows="3"
                                                            wire:model.blur="form.cancellation_reason" class="form-control form-control-lg form-control-solid"
                                                            placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.cancellation_reason')]) }}"></textarea>
                                                    </div>
                                                </div>
                                                <x-panel::error name="form.cancellation_reason" />
                                            @endif
                                        </div>
                                    </td>
                                    <td colspan="6">
                                        <div class="card p-0 h-100">
                                            <div class="card-body p-3 border rounded">
                                                <!-- Flex container for fields -->

                                                <!-- Amount Section -->
                                                <div class="row mb-2 align-items-center">
                                                    <label for="job_total_amount"
                                                        class="col-md-6 col-form-label text-md-end"><strong>{{ __('company.input.total_amount') }}</strong>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="number" id="job_total_amount" min="0"
                                                            step="1" {{-- wire:model.blur="form.job_total_amount" --}} readonly
                                                            value="{{ $form->job_total_amount ?? 0 }}"
                                                            class="form-control form-control-sm form-control-solid"
                                                            placeholder="Enter Amount">
                                                        <x-panel::error name="form.job_total_amount" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="row mb-2 align-items-center">
                                                        <label for="inquiry_pesting_charge"
                                                            class="col-md-6 col-form-label text-md-end"><strong>{{ __('company.input.pesting_charge') }}
                                                            </strong></label>
                                                        <div class="col-md-6">
                                                            <input type="number" id="inquiry_pesting_charge"
                                                                min="0" step="1"
                                                                wire:model.blur="form.inquiry_pesting_charge"
                                                                class="form-control form-control-sm form-control-solid">
                                                            <x-panel::error name="form.inquiry_pesting_charge" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="row mb-2 align-items-center">
                                                        <label for="inquiry_fitting_charge"
                                                            class="col-md-6 col-form-label text-md-end"><strong>{{ __('company.input.fitting_charge') }}</strong></label>
                                                        <div class="col-md-6">
                                                            <input type="number" id="inquiry_fitting_charge"
                                                                min="0" step="1"
                                                                wire:model.blur="form.inquiry_fitting_charge"
                                                                class="form-control form-control-sm form-control-solid">
                                                            <x-panel::error name="form.inquiry_fitting_charge" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Transportation Section -->
                                                <div>
                                                    <div class="row mb-2 align-items-center">
                                                        <label for="inquiry_transportation"
                                                            class="col-md-6 col-form-label text-md-end "><strong>{{ __('company.input.transportation_charge') }}</strong></label>
                                                        <div class="col-md-6">
                                                            <input type="number" id="inquiry_transportation"
                                                                min="0" step="1"
                                                                wire:model.blur="form.inquiry_transportation"
                                                                class="form-control form-control-sm form-control-solid">
                                                            <x-panel::error name="form.inquiry_transportation" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2 align-items-center">
                                                    <label for="discount"
                                                        class="col-md-6 col-form-label text-md-end"><strong>{{ __('company.input.discount') }}</strong>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="number" id="discount" min="0"
                                                            step="1" wire:model.blur="form.discount"
                                                            class="form-control form-control-sm form-control-solid"
                                                            placeholder="Enter discount">
                                                        <x-panel::error name="form.discount" />
                                                    </div>
                                                </div>
                                                <div class="row mb-2 align-items-center">
                                                    <label for="rounded_amount"
                                                        class="col-md-6 col-form-label text-md-end"><strong>{{ __('company.input.rounded_amount') }}</strong>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="number"
                                                            id="rounded_amount" min="0"
                                                            step="1" wire:model.blur="form.rounded_amount"
                                                            class="form-control form-control-sm form-control-solid"
                                                            placeholder="Enter rounded amount">
                                                        <x-panel::error name="form.rounded_amount" />
                                                    </div>
                                                </div>
                                                <hr class="w-100 mt-1">
                                                <span class="fw-bold ms-2">{{ __('company.input.estimated_amount') }}
                                                    <span class=" ms-5">{{ $form->estimateCount ?? 0 }}</span>
                                                </span>
                                                <x-panel::error name="form.estimateCount" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </x-panel::table.main>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <input type="hidden" id="button_status" wire:model="form.button_status"
                class="form-control form-control-lg form-control-solid"> --}}
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.inquiry.index') }}" class="btn btn-light btn-active-light-primary me-2"
                    id="cancelButton">{{ __('app.panel.cancel') }}</a>
                <button type="button" wire:click="save" class="btn btn-primary"
                    id="submitInquiryButton">{{ __('app.panel.submit') }}</button>
            </div>
        </form>
    </div>
</div>
@script
    <script>
        Fancybox.bind("[data-fancybox]", {
            hideScrollbar: false
        });
        Alpine.data('inquiry', () => {

            return {
                measurement_unit: @js($form->measurement_unit ?? 'inch'),
                width: {{ $form->width ?? 0 }},
                height: {{ $form->height ?? 0 }},


                init() {
                    this.$watch('$wire.form.product_id', value => {
                        @this.call("updateProductData", value);
                        $('#product_id').trigger('change.select2');
                    });

                    this.$watch('$wire.form.measurement_unit', value => {
                        this.measurement_unit = value || 'inch';
                    });

                    this.$watch('$wire.form.width', value => {
                        this.width = value || 0;
                    });

                    this.$watch('$wire.form.height', value => {
                        this.height = value || 0;
                    });

                    window.addEventListener('resetAlpineState', () => {
                        this.width = 0;
                        this.height = 0;
                    });
                    // // window.addEventListener('scrollToTop', () => {
                    //     window.scrollTo({
                    //         top: 0,
                    //         behavior: 'smooth'
                    //     });
                    // });

                },
                convertToFeet(field) {
                    // Only convert when "In Inch" is Yes
                    if (this.measurement_unit !== 'inch') return;

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

            $('#measurement_unit').on('change', function() {
                var selectedMeasurementUnit = $(this).val();
                @this.set('form.measurement_unit', selectedMeasurementUnit);
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

            $('#product_id').on('select2:opening', function(e) {
                const selectedCustomerId = $('#customer_id').val();
                if (!selectedCustomerId) {
                    e.preventDefault();
                    alert('Please select a customer first');
                }
            });

            preSelectValues();

        });
    </script>
@endscript