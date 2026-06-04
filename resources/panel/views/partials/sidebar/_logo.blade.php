<!--begin::Logo-->
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    {{-- <a href="{{ route('admin.dashboard') }}"> --}}
        @php
            use App\Models\Company;
            $user = Auth::user();
            $companyLogo = '';

            if (array_key_exists('company_id',$user->toArray())) {
                $userWithMedia = Company::with('media')->where('id', $user->company_id)->first();
                $companyLogo = $userWithMedia->getFirstMediaUrl('company_logo');
            }
            
            if(!empty($companyLogo)) {
                $logo = $companyLogo;
            } else {
                $logo = mix('build/panel/images/logo/logo.png');
            }
        @endphp
        <img alt="Logo" src="{{ $logo }}" class="app-sidebar-logo-default"
            style="height: 60px" />
        {{-- <img alt="Logo" src="{{ mix('build/panel/images/logo/small-logo.png') }}" class="app-sidebar-logo-minimize"
            style="height: 36px" /> --}}
        {{-- </a> --}}
    <div id="kt_app_sidebar_toggle"
        class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
        data-kt-toggle-name="app-sidebar-minimize">
        <i class="ki-duotone ki-black-left-line fs-3 rotate-180"><span class="path1"></span><span
                class="path2"></span></i>
    </div>
    <!--end::Sidebar toggle-->
</div>
<!--end::Logo-->