<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title?(ucwords($title).' | '.config('app.name')):config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ mix('build/panel/images/logo/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ mix('build/panel/vendors/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('build/panel/vendors/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ mix('build/panel/css/common.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ mix('build/panel/css/cropper.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('build/vendor/fancybox/fancybox.css')}}">
    <script src="{{asset('build/vendor/fancybox/fancybox.umd.js')}}"></script>
  <script src="{{ mix('build/panel/js/cropper.js') }}"></script>
  <script src="{{ mix('build/panel/js/common.js') }}"></script>
    @stack('header')
    @livewireStyles
</head>
<body id="kt_app_body" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
      data-kt-app-header-fixed="true"
      data-kt-sticky-app-header-minimize="off"
      data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
      data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
      data-kt-app-header-minimize="on"
      data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
<script src="{{ mix('build/panel/js/theme-mode.js') }}"></script>
@include('panel::partials._page-loader')
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid" id="kt_app_page">
        @include('panel::partials._header')
        <div class="app-wrapper  flex-column flex-row-fluid" id="kt_app_wrapper">
            @include('panel::partials.sidebar.main')
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content flex-column-fluid py-3">
                        <div id="kt_app_content_container" class="container-fluid">{{ $slot }}</div>
                    </div>
                </div>
              {{--  @include('panel::partials._footer')--}}
            </div>
        </div>
    </div>
</div>
@include('panel::partials._scrolltop')
<script src="{{ mix('build/panel/vendors/plugins.bundle.js') }}"></script>
<script src="{{ mix('build/panel/vendors/scripts.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@livewireScripts
@stack('footer')
</body>
</html>
