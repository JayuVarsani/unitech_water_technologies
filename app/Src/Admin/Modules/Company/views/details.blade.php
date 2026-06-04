
<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>

        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{ $title }}</h3>
            </div>
        </div>

        <div class="card-body border-top p-6">
            <div class="row mb-6">
                <div class="col-md-3">
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.company_name') }}: 
                            <span class="fw-normal">{{ $company->name }}</span>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.email') }}: 
                            <span class="fw-normal">{{ $company?->company_staff?->email }}</span>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.email') }}: 
                            <span class="fw-normal">{{ $company?->company_staff?->email }}</span>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.email') }}: 
                            <span class="fw-normal">{{ $company?->company_staff?->email }}</span>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.email') }}: 
                            <span class="fw-normal">{{ $company?->company_staff?->email }}</span>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.email') }}: 
                            <span class="fw-normal">{{ $company?->company_staff?->email }}</span>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">
                            {{ __('admin.input.email') }}: 
                            <span class="fw-normal">{{ $company?->company_staff?->email }}</span>
                        </label>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>