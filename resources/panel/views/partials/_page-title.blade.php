<div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
     data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
     class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
        {{ ucwords($title) }}
    </h1>
    @if (isset($breadcrumb)&&count($breadcrumb))
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
{{--            <li class="breadcrumb-item text-muted">--}}
{{--                <a href="{{route('admin.dashboard')}}"--}}
{{--                   class="text-muted text-hover-primary">{{__('admin.dashboard')}}</a>--}}
{{--            </li>--}}
            @foreach ($breadcrumb as [$crumbTitle,$crumbUrl])
                <li class="breadcrumb-item text-muted">
                    <a href="{{ $crumbUrl }}" class="text-muted text-hover-primary">{{ ucwords($crumbTitle) }}</a>
                </li>
                @if(!$loop->last)
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
</div>
