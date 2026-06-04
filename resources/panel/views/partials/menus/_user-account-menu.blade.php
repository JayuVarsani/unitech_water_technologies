<!--begin::User account menu-->
@if(Auth::user()->getMorphClass() == App\Models\Moderator::class)
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="{{route('admin.profile.index')}}" class="menu-link px-5">
                My Profile
            </a>
        </div>
        <div class="menu-item px-5">
            <a href="{{route('admin.profile.change-password')}}" class="menu-link px-5">
                Change Password
            </a>
        </div>
        <!--end::Menu item-->
        @livewire('panel.logout-link', ['variant' => 'dropdown'])
    </div>
    <!--end::User account menu-->
@endif
@if(Auth::user()->getMorphClass() == App\Models\Staff::class)
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="{{route('company.profile.index')}}" class="menu-link px-5">
                My Profile
            </a>
        </div>
        <div class="menu-item px-5">
            <a href="{{route('company.profile.change-password')}}" class="menu-link px-5">
                Change Password
            </a>
        </div>
        <!--end::Menu item-->
        @livewire('panel.logout-link', ['variant' => 'dropdown'])
    </div>
    <!--end::User account menu-->
@endif