<div x-data="jobcard">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save" />
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{ $title }}</h3>
            </div>
        </div>
        <div class="me-2 mb-6" style="margin-left:90%" x-data="jobcard">
                    <button class="btn btn-sm btn-flex btn-warning fw-bold btn-sm"
                     x-ref="filterDiv"
                            data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown w-1000px w-md-1000px" data-kt-menu="true">
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                        </div>
                        <div class="separator border-gray-200"></div>
                        <div class="px-7 py-5 row">
                            
                            
                            <div class="mb-10 col-md-6">
                                <label class="form-label fw-semibold">Range :</label>
                                <div>
                                    <input type="text" class="form-control" x-ref="timeRange"
                                           placeholder="{{__('app.panel.plc_select',['name'=>'range'])}}"
                                           x-bind:value="rangeValue"
                                           value="" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <button type="button" x-on:click="handleReset"
                                            class="btn btn-sm btn-light btn-active-light-primary me-2"
                                            data-kt-menu-dismiss="true">
                                        Reset
                                    </button>
                                    <button type="button" x-on:click="handleFilter" class="btn btn-sm btn-primary"
                                            data-kt-menu-dismiss="true">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <form class="form" method="post" wire:submit.prevent="save" enctype="multipart/form-data">
            @csrf
            @if($jobcard->isNotEmpty() || $jobcardcom->isNotEmpty())
            <div class="card-body border-top p-6">
                <div class="row g-5">
                    <div class="col-lg-2">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Pending Jobs</h3>
                            </div>
                            <div class="card-body">
                            @foreach($jobcard as $single_job)
                                    <a href="#"
                                    class="btn"
                                    :class="selectedJob === '{{ $single_job->job_no }}' ? 'btn-primary' : ''"
                                    @click.prevent="selectJob('{{ $single_job->job_no }}',  @js($single_job))">
                                        #{{ $single_job->job_no }}
                                    </a><br>
                                @endforeach
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title"># <span x-text="selectedJob"></span></h3>
                            </div>
                            <div class="card-body">
                                <div style="margin-left:80%">
                                    <img class="mw-100 mh-300px card-rounded" alt=""
                                         :src="selectedJobDetails.media?.[0]?.original_url || ''" />
                                </div>
                                <div style="margin-top:-25%">
                                    <div class="px-4">
                                        <h3 class="card-title">Job Name</h3>
                                        <p class="card-title" x-text="selectedJobDetails.description"></p>
                                    </div>
                                    <hr class="w-25 mt-1">
                                    <div class="px-4">
                                        <h3 class="card-title">Product Name</h3>
                                        <p class="card-title" x-text="selectedJobDetails.product_name"></p>
                                    </div>
                                    <hr class="w-25 mt-1">
                                    <div class="px-4">
                                        <h5 class="card-title">Customer: <span x-text="selectedJobDetails.customer?.name"></span></h5>
                                        <h5 class="card-title">Qty: <span x-text="selectedJobDetails.qty"></span></h5>
                                        <h5 class="card-title">Sq.ft: <span x-text="selectedJobDetails.sq_ft"></span></h5>
                                    </div>
                                </div>
                                <hr class="w-25 mt-1">
                                <div class="row mt-4">
                                    <template x-for="(status, key) in statuses" :key="key">
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :id="key" x-model="statuses[key]" />
                                                <label class="form-check-label" :for="key" x-text="key"></label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <hr>
                                Note: <span x-text="selectedJobDetails.description"></span>
                            </div>
                            <div class="card-footer">
                            <button type="button" class="btn btn-primary me-2" style="margin-left:26%" data-bs-toggle="modal" data-bs-target="#wastageModal" x-on:click="openWastageModal(selectedJobDetails.id)">
                            Wastage
                        </button>
                                <button type="submit" class="btn btn-primary me-2" style="margin-left:32%" @click="$wire.markAsPrinted(selectedJobDetails.id)">Printing Done</button>
                            </div>
                        </div>
                    </div>

                    

                    <div class="col-lg-2">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Completed Jobs</h3>
                            </div>
                            <div class="card-body">

                            @foreach($jobcardcom as $single_job)
                                    <a href="#"
                                    class="btn"
                                    :class="selectedJob === '{{ $single_job->job_no }}' ? 'btn-primary' : ''"
                                    @click.prevent="selectJob('{{ $single_job->job_no }}', {{ json_encode($single_job) }})">
                                        #{{ $single_job->job_no }}
                                    </a><br>
                                @endforeach

                           
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
            @else
    <div class="alert alert-warning">
        No Jobs Found.
    </div>
@endif

        </form>
        
    <div wire:ignore.self class="modal fade" id="wastageModal" tabindex="-1" aria-labelledby="wastageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wastageModalLabel">What type of Damages happened?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveWastage">
                    
                        <!-- Radio buttons for selecting damage type -->
                        <div class="mb-3">
                            <div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="damageType" id="wholeProduct" value="whole_product" wire:model="damageType" @click="showMaterialList = false" >
                                    <label class="form-check-label" for="wholeProduct">Whole Product</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="damageType" id="materials" value="materials" wire:model="damageType" @click="showMaterialList = true">
                                    <label class="form-check-label" for="materials">Materials</label>
                                </div>
                            </div>
                        </div>

                        <!-- Show Material List when 'Materials' is selected -->
                        <div x-show="showMaterialList" x-transition>
    <div class="mb-3">
        <label for="materials" class="form-label">Select Materials</label>
        @foreach($materials as $material)
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" 
                       id="material_{{ $material->id }}" 
                       value="{{ $material->id }}" 
                       wire:model="selectedMaterials" 
                       @click="showDimensions[{{ $material->id }}] = !showDimensions[{{ $material->id }}]">
                <label class="form-check-label" for="material_{{ $material->id }}">{{ $material->material_name }}</label>
            </div>

            <!-- Show height and width input fields for selected materials -->
            <div x-show="showDimensions[{{ $material->id }}]" x-transition>
                <div class="row">
                    <div class="col-md-6">
                        <input type="number" class="form-control" 
                               id="height_{{ $material->id }}" 
                               wire:model="dimensions.{{ $material->id }}.height" 
                               placeholder="Height">
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control" 
                               id="width_{{ $material->id }}" 
                               wire:model="dimensions.{{ $material->id }}.width" 
                               placeholder="Width">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

                        <!-- Text area for additional notes -->
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" rows="2" wire:model="note"></textarea>
                        </div>

                        <!-- Show Save button only when a material is selected and dimensions are provided -->
                        <button type="submit" class="btn btn-primary" >Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
</div>

@script
<script>
   Alpine.data('jobcard', () => ({
        selectedJob: @json($jobcard->isNotEmpty() ? $jobcard[0]->job_no : ($jobcardcom->isNotEmpty() ? $jobcardcom[0]->job_no : null)),
        selectedJobDetails: @json($jobcard->isNotEmpty() ? $jobcard[0] : ($jobcardcom->isNotEmpty() ? $jobcardcom[0] : null)),
        statuses: {},
        showMaterialList: false,
        selectedMaterials: @entangle('selectedMaterials'), 
        showDimensions: {},
        startDate: "",
        endDate: "",

        init() {
            if (this.selectedJobDetails) {
                this.updateStatuses(this.selectedJobDetails); 
            }
            this.$watch('selectedMaterials', value => {
                    value.forEach(id => {
                    if (!this.showDimensions[id]) {
                        this.showDimensions[id] = true; // Show inputs for newly selected materials
                    }
                });
            });
            this.startDate = this.$wire.startDate;
            this.endDate = this.$wire.endDate;
            this.initDateRangePicker();

        },
        initDateRangePicker() {
                const th = this;
                $(this.$refs.timeRange).daterangepicker({
                    opens: "left",
                    startDate: th.startDate ? moment(th.startDate) : moment(new Date()),
                    endDate: th.endDate ? moment(th.endDate) : moment(new Date()),
                    locale: { format: "DD-MM-YYYY" }
                }).on("cancel.daterangepicker", function() {
                    th.dateRangeStatic();
                }).on("apply.daterangepicker", function(ev, picker) {
                    th.startDate = picker.startDate.format("YYYY-MM-DD");
                    th.endDate = picker.endDate.format("YYYY-MM-DD");
                    th.dateRangeStatic();
                });
            },
            dateRangeStatic() {
                const filterDiv = this.$refs.filterDiv;
                filterDiv.dataset["ktMenuStatic"] = true;
                setTimeout(() => filterDiv.dataset["ktMenuStatic"] = false, 500);
            },
            rangeValue() {
                return (this.startDate && this.endDate) ? moment(this.startDate).format("DD-MM-YYYY") + " - " + moment(this.endDate).format("DD-MM-YYYY") : "";
            },
            handleReset() {
              
                this.$wire.startDate = "";
                this.$wire.endDate = "";
                this.init();
                this.$wire.$refresh();
            },
            handleFilter() {
               
                this.$wire.startDate = this.startDate;
                this.$wire.endDate = this.endDate;
                this.$wire.$refresh();
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
                    Framing: jobDetails.framing_charge > 0,
                };
            }
        },
        markAsPrinted(jobId) {
          
            $wire.markAsPrinted(jobId).then(() => {
               
                this.refreshJobList();
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
        
        openWastageModal(jobId) {
    @this.set('jobId', jobId); // Dynamically set job_id in Livewire
    const modalElement = document.getElementById('wastageModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}
        // Set selectedJobId in Livewire
    

    }));
   
    
</script>
@endscript