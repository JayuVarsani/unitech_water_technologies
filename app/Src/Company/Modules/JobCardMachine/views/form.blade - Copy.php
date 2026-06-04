<div x-data="jobcard">
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save" />
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{ $title }}</h3>
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
                                    @click.prevent="selectJob('{{ $single_job->job_no }}', {{ json_encode($single_job) }})">
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
                            <button type="button" class="btn btn-primary me-2" style="margin-left:26%" data-bs-toggle="modal" data-bs-target="#wastageModal" x-on:click="openWastageModal">
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
        <div class="modal fade" id="wastageModal" tabindex="-1" aria-labelledby="wastageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wastageModalLabel">What type of Damages happened?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="saveWastage">
                
                    <div class="mb-3">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="damageType" id="wholeProduct" value="whole_product" wire:model="damageType">
                                <label class="form-check-label" for="wholeProduct">Whole Product</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="damageType" id="materials" value="materials" wire:model="damageType">
                                <label class="form-check-label" for="materials">Materials</label>
                            </div>
                        </div>
                    </div>

                    @if($damageType === 'materials')
                        <div class="mb-3">
                            <label for="materials" class="form-label">Select Materials</label>
                            @foreach($materials as $material)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="material_{{ $material->id }}" value="{{ $material->id }}" wire:model="selectedMaterials">
                                    <label class="form-check-label" for="material_{{ $material->id }}">{{ $material->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        @foreach($selectedMaterials as $materialId)
                            <div class="mb-3">
                                <h6>Dimensions for {{ $materials->find($materialId)->name ?? 'Material' }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="height_{{ $materialId }}" class="form-label">Height</label>
                                        <input type="number" class="form-control" id="height_{{ $materialId }}" wire:model="dimensions.{{ $materialId }}.height">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="width_{{ $materialId }}" class="form-label">Width</label>
                                        <input type="number" class="form-control" id="width_{{ $materialId }}" wire:model="dimensions.{{ $materialId }}.width">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="form-control" id="note" rows="2" wire:model="note"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
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
        materials: @entangle('materials').defer, 

        init() {
            if (this.selectedJobDetails) {
                this.updateStatuses(this.selectedJobDetails); 
            }
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
        
        openWastageModal() 
        {
             
            
    console.log(this.selectedJobDetails);

    if (this.selectedJobDetails) {
        @this.set('selectedJobId', this.selectedJobDetails.id); // Set selectedJobId in Livewire
    }
    const modalElement = document.getElementById('wastageModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show(); 
           
        }

    }));
   
    
</script>
@endscript