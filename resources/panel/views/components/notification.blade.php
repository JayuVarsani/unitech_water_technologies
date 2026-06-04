<div class="app-navbar-item ms-1 ms-md-4" x-data="notification()">
    <div
        class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
        <i class="ki-duotone ki-notification-status fs-2">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
        </i>
        @if($unReadNotificationCount)
            <span
                class="bullet bullet-dot bg-danger h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink">
        </span>
        @endif
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true"
         id="kt_menu_notifications" style="">
        <div class="scroll-y mh-325px my-5 px-8">
            @if($notifications->count())
                @foreach($notifications as $notification)
                    <div class="d-flex flex-stack py-4">
                        <div class="d-flex align-items-center me-2">
                    <span class="text-gray-800 text-hover-primary fw-semibold text-capitalize">
                        @if($notification->data['type']=="gps_off")
                            {{$notification->data['user']['name']}} Turned Gps is off
                        @elseif($notification->data['type']=="at_same_location")
                            {{$notification->data['user']['name']}} is at same location for long time
                        @elseif($notification->data['type']=="gps_on")
                            {{$notification->data['user']['name']}} Turned Gps is on
                        @endif
                    </span>
                        </div>
                        <span class="badge badge-light fs-8">
                        {{Carbon\Carbon::parse($notification->created_at, 'UTC')->setTimezone('Asia/Calcutta')->diffForHumans()}}
                    </span>
                    </div>
                @endforeach
            @else
                No Notification Found
            @endif
        </div>
    </div>
</div>
@script
<script defer>
    const menu = KTMenu.getInstance(document.querySelector("#kt_menu_notifications"));
    Alpine.data('notification', () => {
        return {
            init() {
                this.notifyInterval();
                menu.on("kt.menu.dropdown.hide", async () => {
                    await this.$wire.readAllNotifications()
                    this.notifyInterval()
                });
            },
            notifyInterval() {
                const $this = this;
                if (!$this.$wire.unReadNotificationCount) {
                    const interval = setInterval(async function () {
                        await $this.$wire.hasNotifications();
                        if ($this.$wire.unReadNotificationCount) {
                            clearInterval(interval);
                        }
                    }, 15000);
                }
            }
        }
    });
</script>
@endscript
