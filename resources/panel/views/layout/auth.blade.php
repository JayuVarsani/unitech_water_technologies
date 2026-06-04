<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title?($title.' | '.config('app.name')):config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ mix('build/panel/images/logo/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ mix('build/panel/vendors/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('build/panel/vendors/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ mix('build/panel/css/common.css') }}">
    @stack('header')
    @livewireStyles
</head>
<body id="kt_body" class="app-blank">
@include('panel::partials._page-loader')
<div class="d-flex flex-column flex-root" id="kt_app_root">{{ $slot }}</div>
<script src="{{ mix('build/panel/vendors/plugins.bundle.js') }}"></script>
<script src="{{ mix('build/panel/vendors/scripts.bundle.js') }}"></script>
@livewireScripts
</body>
</html>
