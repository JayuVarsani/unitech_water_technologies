<div x-data='table'>
    <x-panel::alert />
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                            class="path2"></span></i>
                    <input type="text" wire:model.live.debounce.800ms="query.search"
                           class="form-control form-control-solid w-250px ps-12"
                           placeholder="{{__('app.panel.search')}} {{__('company.role-permission.title')}}">
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-subscription-table-toolbar="base">
                    <a href="{{route('company.staff-management.role-permission.create')}}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i> {{__('app.panel.create_name',['name' => __('company.role-permission.title')])}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <x-panel::table.main :items="$items" perPageName="query.perPage">
                <x-panel::table.head>
                    <!-- <th class="min-w-125px">{{__('app.panel.table.id')}}</th> -->
                    <th class="min-w-125px text-start text-black">{{__('company.input.name')}}</th>
                    <th class="min-w-125px text-black">{{__('app.panel.table.action')}}</th>
                </x-panel::table.head>
                <x-panel::table.body :items="$items">
                    @foreach($items as $key=>$item)
                        <tr wire:key="{{$item->id}}">
                            <!-- <td>{{$item->id}}</td> -->
                            <td class="text-start">
                                <span class="text-gray-800 text-hover-primary mb-1">{{$item->name}}</span>
                            </td>
                            <td>
                                <x-panel::table.action.main>
                                    <a href="{{ route('company.staff-management.role-permission.edit',$item->id) }}"><i class="fa-solid fa-pen icon edit-icon"></i></a>
                                    
                                    @if(!in_array($item->name, ['Designer', 'Accountant', 'Machine operator']))
                                    <a x-on:click="showAlert({{ $item->id }})"><i class="fa-solid fa-trash icon text-danger"></i></a>
                                    @endif
                                </x-panel::table.action.main>
                            </td>
                        </tr>
                    @endforeach
                </x-panel::table.body>
            </x-panel::table.main>
        </div>
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