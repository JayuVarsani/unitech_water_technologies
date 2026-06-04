<div class="app-navbar flex-shrink-0">
    <div class="app-navbar-item ms-1 ms-md-4">
        {{-- @livewire(\Resources\Panel\Components\Notification::class) --}}
        {{-- @include('panel::partials.theme-mode._main') --}}
    </div>
    <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <div class="cursor-pointer symbol symbol-35px d-flex gap-2" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <div class="d-flex flex-column justify-content-center align-items-center fw-bold">
            <p class="mb-0 text-dark fs-7">{{ auth()->user()?->name ?? '-' }}</p>
            <p class="text-muted fs-7 text-truncate mb-0">{{ auth()->user()->email ?? '-' }}</p>
        </div>
            <img src="{{ auth()->user()->getfirstMediaUrl('profile_image') }}" class="rounded-3" alt="user" />
        </div>
        @include('panel::partials.menus._user-account-menu')
    </div>
</div>
