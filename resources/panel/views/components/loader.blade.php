@props(['target'=>null])
<div class="app-page-loader justify-content-center align-items-center bg-black text-white flex-column opacity-50"
     @if($target)
         wire:target="{{$target}}"
     @endif
     wire:loading.flex>
    <span class="spinner-border text-primary" role="status"></span>
    <span class="text-muted fs-6 fw-semibold mt-5">{{__('app.panel.loader')}}</span>
</div>
