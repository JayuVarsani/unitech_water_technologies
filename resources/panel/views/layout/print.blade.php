<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title ? $title . ' | ' . config('app.name') : config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ mix('build/panel/images/logo/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ mix('build/panel/vendors/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('build/panel/vendors/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ mix('build/panel/css/common.css') }}">
    <link rel="stylesheet" href="{{asset('build/vendor/fancybox/fancybox.css')}}">
    <script src="{{asset('build/vendor/fancybox/fancybox.umd.js')}}"></script>
    @stack('header')
    @livewireStyles
    <style>
        @media print {
            .buttons {
                display: none !important;
            }
        }
    </style>
</head>

<body id="kt_body" class="app-blank">
    @include('panel::partials._page-loader')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="m-2 d-flex buttons">
            <a href="{{ url()->previous() }}" class="btn btn-light btn-active-light-primary me-2"
                id="cancelButton">{{ __('app.panel.back') }}</a>
            <button class="btn btn-primary" onclick="window.print()">{{ __('app.panel.print') }}</button>
        </div>
        {{ $slot }}
    </div>
    <script src="{{ mix('build/panel/vendors/plugins.bundle.js') }}"></script>
    <script src="{{ mix('build/panel/vendors/scripts.bundle.js') }}"></script>
    @livewireScripts
</body>

</html>
