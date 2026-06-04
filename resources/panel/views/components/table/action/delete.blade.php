@props(['click','confirm'=>null,'module'])
<a style="cursor: pointer"
   wire:confirm="{{$confirm??ucfirst(__('app.panel.are_you_sure_want_to_delete', ['name' => $module]))}}"
   wire:click="{{$click}}"
   data-toggle="tooltip" data-placement="top" title="Delete {{$module}} Details">
    <i class="fa-solid fa-trash icon text-danger" ></i>
</a>
