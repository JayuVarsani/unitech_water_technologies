<div x-data="jobcard">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save" />
        
        <style>
            .form-check-input:disabled {
    pointer-events: none; /* Prevent interaction */
    filter: none;
    opacity: 1; /* Remove transparency */
}
.form-check-input:disabled~.form-check-label, .form-check-input[disabled]~.form-check-label {
    cursor: default;
    opacity: 1;
}
[x-cloak] { display: none !important; }
        </style>
        <div class="me-2 mb-4" style="margin-left:90%; margin-top:1%;" x-data="jobcard">
            <button class="btn btn-sm btn-flex btn-primary fw-bold btn-sm" x-ref="filterDiv" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="fa-solid fa-filter"></i> Filter
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
                            <input type="text" class="form-control" x-ref="timeRange" placeholder="{{__('app.panel.plc_select',['name'=>'range'])}}" x-bind:value="rangeValue" value="" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <button type="button" x-on:click="handleReset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">
                                Reset
                            </button>
                            <button type="button" x-on:click="handleFilter" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form class="form" method="post" wire:submit.prevent="save" enctype="multipart/form-data">
            @csrf

           

             
            <div class="card-body border-top p-6">
                <div class="row g-5">

                    <div class="col-lg-2">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title" style="color:#1b84ff">Pending Jobs</h3>
                            </div>
                            <div class="card-body">
                            @if($jobcarddata->isNotEmpty() )
                                @foreach($jobcarddata as $single_job)
                                <a href="#" class="btn" :class="selectedJob === '{{ $single_job->job_no }}' ? 'btn-primary' : ''" @click.prevent="selectJob('{{ $single_job->job_no }}',  @js($single_job))">
                                        #{{ $single_job->job_no }}
                                    </a>
                                    
                                <br><hr class="w-100"> @endforeach
                                @else
                                        No Data found
                                @endif

                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card card-stretch card-bordered mb-2">
                        @if($jobcarddata->isNotEmpty() || $jobcardcom->isNotEmpty())
                            <div class="card-header">
                                <h3 class="card-title"># <span x-text="selectedJob"></span></h3>
                            </div>
                            <div class="card-body">
                                <div style="margin-left:80%">
                                    <img class="mw-100 mh-300px card-rounded" alt="" :src="selectedJobDetails.media?.[0]?.original_url || ''" />
                                </div>
                                <div>
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
                                                <input class="form-check-input" type="checkbox" :id="key" x-model="statuses[key]" disabled />
                                                <label class="form-check-label card-title" :for="key" x-text="key"></label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <hr> Note: <span x-text="selectedJobDetails.description"></span>
                            </div>
                            <div class="card-footer text-center align-items-center py-2" x-show="selectedJobDetails.job_status === 'pending'" x-cloak>
                                <button type="button" class="btn btn-primary me-2"  data-bs-toggle="modal" data-bs-target="#wastageModal" x-on:click.prevent="openWastageModal(selectedJobDetails.id)">
                                    Wastage </span>
                                </button>
                                <button type="submit" class="btn btn-primary me-2"  @click="$wire.markAsPrinted(selectedJobDetails.id)">Printing Done</button>
                            </div>
                            @else
                                        
                                @endif
                        </div>
                    </div>



                    <div class="col-lg-2">
                        <div class="card card-stretch card-bordered mb-5">
                            <div class="card-header">
                                <h3 class="card-title" style="color:#1b84ff">Completed Jobs</h3>
                            </div>
                            <div class="card-body">
                            @if($jobcardcom->isNotEmpty() )
                                @foreach($jobcardcom as $single_job)
                                <a href="#" class="btn" :class="selectedJob === '{{ $single_job->job_no }}' ? 'btn-primary' : ''" @click.prevent="selectJob('{{ $single_job->job_no }}', {{ json_encode($single_job) }})">
                                        #{{ $single_job->job_no }}
                                    </a>
                                <br><hr class="w-100 "> @endforeach
                                @else
                                        No Data found
                                @endif

                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
          

        </form>
        <form wire:ignore.self x-on:submit.prevent="saveWastage">
        <div wire:ignore.self class="modal fade" id="wastageModal" tabindex="-1" aria-labelledby="wastageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wastageModalLabel">What type of Damages happened?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       
                                 <input type='hidden' x-model="jobId" wire:model="jobId" >
                            <!-- Radio buttons for selecting damage type -->
                            <div class="mb-3">
                                <div>
                                    <div class="form-check mb-2 ">
                                        <input class="form-check-input text-black" type="radio" name="damageType" id="wholeProduct" value="whole_product" wire:model="damageType" @click="showMaterialList = false">
                                        <label class="form-check-label text-black" for="wholeProduct">Whole Product</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input text-black" type="radio" name="damageType" id="materials" value="materials" wire:model="damageType" @click="showMaterialList = true">
                                        <label class="form-check-label text-black " for="materials">Materials</label>
                                    </div>
                                    
                                </div>
                            </div>

                            <!-- Show Material List when 'Materials' is selected -->
                            <div x-show="showMaterialList" x-transition>
                                <div class="mb-3">
                                    <label for="materials" class="form-label">Select Materials</label>
                                    @foreach($materials as $material)
                                    <div class="form-check mb-5">
                                        <input class="form-check-input text-black" type="checkbox" id="material_{{ $material->id }}" value="{{ $material->id }}" wire:model="selectedMaterials" @click="showDimensions[{{ $material->id }}] = !showDimensions[{{ $material->id }}]">
                                        <label class="form-check-label text-black" for="material_{{ $material->id }}">{{ $material->material_name }}</label>
                                    </div>

                                    <!-- Show height and width input fields for selected materials -->
                                    <div x-show="showDimensions[{{ $material->id }}]" x-transition>
                                        <div class="row mb-5" >
                                            <div class="col-md-6">
                                                <input type="number" class="form-control text-black" id="height_{{ $material->id }}" wire:model="dimensions.{{ $material->id }}.height" placeholder="Height">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control text-black" id="width_{{ $material->id }}" wire:model="dimensions.{{ $material->id }}.width" placeholder="Width">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="note" class="form-label required">Note</label>
                                <textarea class="form-control" id="note" rows="2" wire:model="note"></textarea>
                                
                            </div>


                            <button type="submit" class="btn btn-primary">Save</button>
                        
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

@script
<script defer>
    const modalEl = document.getElementById('wastageModal');
    const modal = new bootstrap.Modal(modalEl);
    var jobCardUrl = "{{ route('company.jobcard.index') }}"; 
    Alpine.data('jobcard', () => {
        return {
        jobId: null,
        selectedJob: @json($jobcarddata->isNotEmpty() ? $jobcarddata[0]->job_no : ($jobcardcom->isNotEmpty() ? $jobcardcom[0]->job_no : null)),
        selectedJobDetails: @json($jobcarddata->isNotEmpty() ? $jobcarddata[0] : ($jobcardcom->isNotEmpty() ? $jobcardcom[0] : null)),
        statuses: {},
        showMaterialList: false,
        selectedMaterials: @entangle('selectedMaterials'), 
        showDimensions: {},
        startDate: "",
        endDate: "",
           
            init() {
                modalEl.addEventListener('hide.bs.modal', () => this.resetModalForm());


                // this.$wire.on('jobsFiltered', (pendingJobs, completedJobs) => {
                //     this.updateFilteredJobs(pendingJobs, completedJobs);
                // });

                if (this.selectedJobDetails) 
                {
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
             
             this.initDateRangePicker();
            },


        //     updateFilteredJobs(pendingJobs, completedJobs) {
        //     const safePendingJobs = Array.isArray(pendingJobs) ? pendingJobs : [];
        //     const safeCompletedJobs = Array.isArray(completedJobs) ? completedJobs : [];

        //     // Match the logic used before the filter
        //     const firstPendingJob = safePendingJobs.length > 0 ? safePendingJobs[0] : null;
        //     const firstCompletedJob = safeCompletedJobs.length > 0 ? safeCompletedJobs[0] : null;

        //     this.selectedJob = firstPendingJob?.job_no || firstCompletedJob?.job_no || null;
        //     this.selectedJobDetails = firstPendingJob || firstCompletedJob || null;

        //     // Update statuses
        //     if (this.selectedJobDetails) {
        //         this.updateStatuses(this.selectedJobDetails);
        //     }
        // },












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
            this.$wire.query.startDate = "";
            this.$wire.query.endDate = "";
            this.init();
            this.$wire.$refresh();
            window.location.href = jobCardUrl;
        },

        handleFilter() 
        {
    
   
                this.$wire.query.startDate = this.startDate;
                this.$wire.query.endDate = this.endDate;
                const newUrl = new URL(window.location);
                newUrl.searchParams.set('startDate', this.startDate);
                newUrl.searchParams.set('endDate', this.endDate);
                history.pushState({}, '', newUrl);
                this.$wire.$refresh();
                 window.location.reload();  
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
                Swal.fire({
                    position: "top-end",
                    width: 400,
                    icon: response.success ? "success" : "error",
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000
                });
                if (!response.success) {
                    return;
                }
                modal.hide();
                setTimeout(() => this.$wire.$refresh(), 400);
            },
        };
    });
</script>

@endscript