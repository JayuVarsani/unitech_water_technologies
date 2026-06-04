<div>
    <x-panel::alert />
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="resetPassword" />
        <!-- <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{__('company.change-password.title')}}</h3>
            </div>
        </div> -->
        <form class="form" id="main_form" method="post" wire:submit="resetPassword" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-9">

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                           for="oldPassword">{{__('company.input.current_password')}}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text"
                               name="currentPassword" id="oldPassword"
                               class="form-control form-control-lg form-control-solid"
                               wire:model.blur="currentPassword"
                               placeholder="Please enter Current password">
                        <x-panel::error name="currentPassword" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                           for="password">{{__('company.input.password')}}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text"
                               name="password" id="password" class="form-control form-control-lg form-control-solid"
                               wire:model.blur="password"
                               placeholder="Please enter  password">
                        <x-panel::error name="password" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                           for="confirmPassword">{{__('company.input.confirm_password')}}</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text"
                               name="confirmPassword" id="confirmPassword"
                               class="form-control form-control-lg form-control-solid"
                               wire:model.blur="confirmPassword"
                               placeholder="Please enter confirm password">
                        <x-panel::error name="confirmPassword" />
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{route('company.dashboard')}}"
                   class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
                <button type="submit" class="btn btn-primary">{{__('app.panel.submit')}}</button>
            </div>
        </form>
    </div>
</div>
