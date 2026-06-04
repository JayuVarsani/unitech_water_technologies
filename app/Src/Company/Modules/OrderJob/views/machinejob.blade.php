<div x-data="jobcard">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save" />

        <style>
            .form-check-input:disabled {
                pointer-events: none;
                /* Prevent interaction */
                filter: none;
                opacity: 1;
                /* Remove transparency */
            }

            .form-check-input:disabled~.form-check-label,
            .form-check-input[disabled]~.form-check-label {
                cursor: default;
                opacity: 1;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
        @if($canView)
        {{-- <div class="card-header border-0 pt-6 justify-content-start gap-4">
            <div class="card-title" x-data="jobcard">
                <div class="">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-calendar fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" class="form-control w-250px ps-12" x-ref="timeRange"
                            placeholder="{{ __('app.panel.plc_select', ['name' => 'range']) }}"
                            x-bind:value="rangeValue" value="" />
                    </div>
                </div>
            </div>
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-filter fs-3 position-absolute ms-5"><span class="path1"></span>
                        <span class="path2"></span></i>
                    <select class="form-select form-select-solid w-250px ps-12" wire:model.live="query.orderId">
                        <option value="">{{ __('app.panel.plc_select', ['name' => 'orderId']) }}</option>
                        @foreach ($this->getPendingOrders() as $order)
                            <option value="{{ $order->id }}">{{ $order->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div> --}}

        <form class="form" method="post" wire:submit.prevent="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row g-5">

                    <div class="col-lg-2">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title" style="color:#1b84ff">{{ __('company.jobs.pending_jobs') }}</h3>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @if ($orderjobdata->isNotEmpty())
                                    @foreach ($orderjobdata as $single_job)
                                        <a href="#" class="btn"
                                            :class="selectedJob === '{{ $single_job->job_no }}' ? 'btn-primary' : ''"
                                            @click.prevent="selectJob('{{ $single_job->job_no }}',  @js($single_job))">
                                            #{{ $single_job->job_no }}
                                        </a>

                                        <br>
                                        <hr class="w-100">
                                    @endforeach
                                @else
                                    {{ __('app.panel.table.no_record_found') }}
                                @endif

                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card card-stretch card-bordered mb-2">
                            @if ($orderjobdata->isNotEmpty() || $orderjobcom->isNotEmpty())
                                <div class="card-header py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title mb-0">{{ __('company.order_id') }} : <span
                                                x-text="selectedJobDetails.order.id" style="cursor: pointer;"
                                                onclick="copyOrderId(this)"></span></h3>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title mb-0">{{ __('company.input.jobno') }} : # <span x-text="selectedJob"
                                                style="cursor: pointer;" onclick="copyToClipboard(this)"></span></h3>
                                    </div>

                                </div>

                                <div class="card-body" wire:key="selected_job_details_container">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <!-- Left Side: Details -->
                                        <div class="flex-grow-1 pe-4">

                                            <div class="mb-2">
                                                <h5 class="card-title mb-1">
                                                    <span class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.product_name') }} : </span>
                                                    <span x-text="selectedJobDetails.product_name"></span>
                                                </h5>
                                            </div>
                                            <hr class="mt-1 mb-2 w-25">

                                            <div class="mb-2">
                                                <h5 class="card-title mb-1">
                                                    <span class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.customer') }} : </span>
                                                    <span x-text="selectedJobDetails.order.customer.name"></span>
                                                </h5>
                                                <h5 class="card-title mb-1">
                                                    <span class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.qty') }} : </span>
                                                    <span x-text="selectedJobDetails.qty"></span>
                                                </h5>
                                                <h5 class="card-title mb-1">
                                                    <span class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.sq_ft') }} : </span>
                                                    <span x-text="selectedJobDetails.sq_ft"></span>
                                                </h5>
                                                <p><span class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.narration') }} : </span><span x-text="selectedJobDetails.narration"></span></p>
                                            </div>
                                        </div>

                                        <!-- Right Side: Image -->
                                        <div style="max-width: 200px;" class="text-end">
                                            <img class="mw-100 mh-150px card-rounded shadow-sm"
                                                :src="selectedJobDetails.image_url || ''" style="object-fit: cover;" />
                                        </div>
                                    </div>

                                    <hr class="my-3">
                                    <p class="mt-2" x-show="selectedJobDetails.order.description">
                                        <span class="text-muted fw-bold fs-7 text-uppercase gs-0">{{ __('company.input.note') }}:</span> <span
                                            x-text="selectedJobDetails.order.description"></span>
                                    </p>
                                </div>

                                <div class="card-footer text-center align-items-center py-2"
                                    x-show="selectedJobDetails.status === 'design'" x-cloak>
                                    @if($canCreateWastage)
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#wastageModal"
                                            x-on:click.prevent="openWastageModal(selectedJobDetails.id)">
                                            Wastage
                                        </button>
                                    @endif
                                    @if($canEdit)
                                        <button type="submit" class="btn btn-primary me-2"
                                            @click="$wire.markAsPrinted(selectedJobDetails.id)">
                                            Printing Done
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title" style="color:#1b84ff">{{ __('company.jobs.completed_jobs') }}</h3>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @if ($orderjobcom->isNotEmpty())
                                    @foreach ($orderjobcom as $single_job)
                                        <a href="#" class="btn"
                                            :class="selectedJob === '{{ $single_job->job_no }}' ? 'btn-primary' : ''"
                                            @click.prevent="selectJob('{{ $single_job->job_no }}', {{ json_encode($single_job) }})">
                                            #{{ $single_job->job_no }}
                                        </a>
                                        <br>
                                        <hr class="w-100 ">
                                    @endforeach
                                @else
                                    {{ __('app.panel.table.no_record_found') }}
                                @endif

                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>


        </form>
        <form wire:ignore.self x-on:submit.prevent="saveWastage">
            <div wire:ignore.self class="modal fade" id="wastageModal" tabindex="-1"
                aria-labelledby="wastageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title required" id="wastageModalLabel">What type of Damages happened?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <input type='hidden' x-model="jobId" wire:model="jobId">
                            <!-- Radio buttons for selecting damage type -->
                            <div class="mb-3">
                                <div>
                                    <div class="form-check mb-2 ">
                                        <input class="form-check-input text-black" type="radio" name="damageType"
                                            id="wholeProduct" value="whole_product" wire:model="damageType"
                                            @click="showMaterialList = false" required>
                                        <label class="form-check-label text-black" for="wholeProduct">Whole
                                            Product</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input text-black" type="radio" name="damageType"
                                            id="materials" value="materials" wire:model="damageType"
                                            @click="showMaterialList = true" required>
                                        <label class="form-check-label text-black " for="materials">Materials</label>
                                    </div>
                                    @error('damageType')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Show Material List when 'Materials' is selected -->
                            <div x-show="showMaterialList" x-transition>
                                <div class="mb-3">
                                    <label for="materials" class="form-label required">Select Materials</label>
                                    @foreach ($materials as $material)
                                        <div class="form-check mb-5">
                                            <input class="form-check-input text-black" type="checkbox"
                                                id="material_{{ $material->id }}" value="{{ $material->id }}"
                                                wire:model="selectedMaterials"
                                                @click="showDimensions[{{ $material->id }}] = !showDimensions[{{ $material->id }}]">
                                            <label class="form-check-label text-black"
                                                for="material_{{ $material->id }}">{{ $material->material_name }}</label>
                                        </div>

                                        <!-- Show height and width input fields for selected materials -->
                                        <div x-show="showDimensions[{{ $material->id }}]" x-transition>
                                            <div class="row mb-5">
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control text-black"
                                                        id="height_{{ $material->id }}"
                                                        wire:model="dimensions.{{ $material->id }}.height"
                                                        placeholder="Height" min="1"
                                                        x-bind:required="showDimensions[{{ $material->id }}]">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control text-black"
                                                        id="width_{{ $material->id }}"
                                                        wire:model="dimensions.{{ $material->id }}.width"
                                                        placeholder="Width" min="1"
                                                        x-bind:required="showDimensions[{{ $material->id }}]">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="note" class="form-label required">{{ __('company.input.note') }}</label>
                                <textarea class="form-control" id="note" rows="2" wire:model="note" required></textarea>

                            </div>


                            <button type="submit" class="btn btn-primary">{{ __('app.panel.save') }}</button>

                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>

@script
    <script defer>
        const modalEl = document.getElementById('wastageModal');
        const modal = new bootstrap.Modal(modalEl);
        var orderJobUrl = "{{ route('company.orderjob.index') }}";
        Alpine.data('jobcard', () => {
            return {
                jobId: null,
                selectedJob: @json(
                    $orderjobdata->isNotEmpty()
                        ? $orderjobdata[0]->job_no
                        : ($orderjobcom->isNotEmpty()
                            ? $orderjobcom[0]->job_no
                            : null)),
                selectedJobDetails: @json($orderjobdata->isNotEmpty() ? $orderjobdata[0] : ($orderjobcom->isNotEmpty() ? $orderjobcom[0] : null)),
                statuses: {},
                showMaterialList: false,
                selectedMaterials: @entangle('selectedMaterials'),
                showDimensions: {},
                startDate: "",
                endDate: "",
                orderId: "",
                initInputs() {
                    this.startDate = this.$wire.query.startDate;
                    this.endDate = this.$wire.query.endDate;
                    this.orderId = this.$wire.query.orderId;
                },
                init() {
                    modalEl.addEventListener('hide.bs.modal', () => this.resetModalForm());

                    if (this.selectedJobDetails) {
                        this.updateStatuses(this.selectedJobDetails);
                    }
                    this.$watch('selectedMaterials', value => {
                        value.forEach(id => {
                            if (!this.showDimensions[id]) {
                                this.showDimensions[id] = true;
                            }
                        });
                    });

                    this.startDate = this.$wire.query.startDate;
                    this.endDate = this.$wire.query.endDate;
                    this.orderId = this.$wire.query.orderId;
                    this.initInputs();
                    this.initDateRangePicker();
                },

                resetModalForm() {
                    this.jobId = null;

                },

                openWastageModal(jobId) {
                    // console.log(jobId);

                    this.jobId = jobId;

                    modal.show();
                    // @this.set('jobId', jobId); 
                },
                initDateRangePicker() {
                    const th = this;
                    $(this.$refs.timeRange).daterangepicker({
                        opens: "right",
                        startDate: th.startDate ? moment(th.startDate) : moment(new Date()),
                        endDate: th.endDate ? moment(th.endDate) : moment(new Date()),
                        locale: {
                            format: "DD-MM-YYYY"
                        }
                    }).on("cancel.daterangepicker", function() {
                        th.cancelDateRange();
                    }).on("apply.daterangepicker", function(ev, picker) {
                        th.startDate = picker.startDate.format("YYYY-MM-DD");
                        th.endDate = picker.endDate.format("YYYY-MM-DD");
                        th.setDateRange();
                    });
                },

                setDateRange() {
                    this.$wire.query.startDate = this.startDate;
                    this.$wire.query.endDate = this.endDate;
                    this.init();
                    this.$wire.$refresh();
                },
                cancelDateRange() {
                    this.$wire.query.startDate = "";
                    this.$wire.query.endDate = "";
                    this.init();
                    this.$wire.$refresh();
                },
                rangeValue() {
                    return (this.startDate && this.endDate) ? moment(this.startDate).format("DD-MM-YYYY") + " - " +
                        moment(this.endDate).format("DD-MM-YYYY") : "";
                },

                selectJob(jobNo, jobDetails) {
                    this.selectedJob = jobNo;
                    this.selectedJobDetails = jobDetails;
                    this.selectedJobId = jobDetails.id;
                    this.updateStatuses(jobDetails);
                },

                updateStatuses(jobDetails) {
                    if (jobDetails) {
                        this.statuses = {
                            Fitting: jobDetails.fitting_charge > 0,
                            Pesting: jobDetails.pesting_charge > 0,
                            Transportation: jobDetails.transportation_charge > 0,
                        };
                    }
                },

                markAsPrinted(jobId) {
                    $wire.markAsPrinted(jobId).then(() => {

                        this.refreshJobList();
                        // window.location.reload();
                    });
                },

                refreshJobList() {
                    $wire.refreshJobList().then(updatedJobs => {
                        if (updatedJobs && updatedJobs.length > 0) {
                            this.selectedJob = updatedJobs[0]?.job_no ?? null;
                            this.selectedJobDetails = updatedJobs[0] ?? null;
                            this.updateStatuses(this.selectedJobDetails);
                        }
                    });
                },

                async saveWastage() {
                    const response = await this.$wire.saveWastage(this.jobId);

                    if (!response.success) {
                        return; // Do not close modal if validation fails
                    }


                    Swal.fire({
                        position: "top-end",
                        width: 400,
                        icon: response.success ? "success" : "error",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    // if (!response.success) {
                    //     return;
                    // }
                    modal.hide();
                    setTimeout(() => this.$wire.$refresh(), 400);
                },
            };
        });

    </script>
@endscript
