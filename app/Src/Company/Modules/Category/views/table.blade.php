<div x-data='table'>
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6">
            @if($canView)
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                            <span class="path2"></span></i>
                        <input type="text" wire:model.live.debounce.800ms="query.search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="{{__('app.panel.search_name', ['name'=>__('company.category')])}}">
                    </div>
                </div>
            @endif
            @if($canCreate)
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('company.category.create')}}" class="btn btn-primary btn-sm">
                            <i class="ki-duotone ki-plus fs-2"></i> {{__('app.panel.create_name', ['name' =>
                            __('company.category')])}}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        @if($canView)
            <div class="card-body pt-0">
                <x-panel::table.main :items="$items">
                    <x-panel::table.head>
                        <th class="min-w-125px text-start text-black">{{ __('company.id') }}</th> 
                        <th class="min-w-125px text-start text-black">{{ __('company.input.name') }}</th>
                       
                        
                        @if($canEdit || $canDelete)
                            <th class="min-w-125px text-black">{{ __('company.action') }}</th>
                        @endif
                    </x-panel::table.head>
                    <x-panel::table.body :items="$items">
                        @foreach($items as $key=>$item)
                        <tr wire:key="{{ $item->id }}">
                             <td class="text-start">{{ $items->total() - (($items->currentPage() - 1) * $items->perPage()) - ($loop->iteration - 1) }}</td> 
                            <td class="text-start">{{ $item->name }}</td>
                            
                           
                            @if($canEdit || $canDelete)
                                <td>
                                    <x-panel::table.action.main>
                                        @if($canEdit)
                                            <x-panel::table.action.edit :route="route('company.category.edit',$item->id)" module="{{__('company.category')}}" />
                                        @endif
                                        @if($canDelete)
                                            <a x-on:click="showAlert({{ $item->id }})"><i class="fa-solid fa-trash icon text-danger"></i></a>
                                        @endif
                                    </x-panel::table.action.main>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </x-panel::table.body>
                </x-panel::table.main>
            </div>
        @endif
    </div>
</div>
@script
<script>
    Alpine.data('table',()=>{
        return {
            showAlert(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$wire.delete(id);
                    }
                });
            }
        };
    });
</script>
@endscript