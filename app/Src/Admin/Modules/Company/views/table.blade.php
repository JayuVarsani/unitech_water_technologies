<div x-data='table'> 
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span>
                        <span class="path2"></span></i>
                    <input type="text" wire:model.live.debounce.800ms="query.search"
                        class="form-control form-control-solid w-250px ps-12"
                        placeholder="{{__('app.panel.search_name', ['name'=>__('admin.company')])}}">
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end">
                    <a href="{{route('admin.company.create')}}" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-plus fs-2"></i> {{__('app.panel.create_name', ['name' =>
                        __('admin.company')])}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <x-panel::table.main :items="$items">
                <x-panel::table.head>
                    
                    <th class="min-w-125px text-start text-black">{{ __('admin.input.company_logo') }}</th>
                    <th class="min-w-125px text-start text-black">{{ __('admin.input.name') }}</th>
                    <th class="min-w-125px text-start text-black">{{ __('admin.input.contact_number') }}</th>
                    <th class="min-w-125px text-start text-black">{{ __('admin.input.email') }}</th>
                    <th class="min-w-125px text-start text-black">{{ __('admin.input.gst_no') }}</th>
                    <th class="min-w-125px text-start text-black">{{ __('admin.input.status') }}</th>
                    <th class="min-w-125px text-black">{{ __('admin.action') }}</th>
                </x-panel::table.head>
                <x-panel::table.body :items="$items">
                    @foreach($items as $key=>$item)
                    <tr wire:key="{{ $item->id }}">
                        
                        <td class="text-start">@if(!empty($item->getfirstMediaUrl('company_logo','compressed')))
                            <img src="{{ $item->getfirstMediaUrl('company_logo','compressed') }}" style="height: 50px;width:50px;" alt="company logo"/>@else {{ '-' }} @endif</td>
                        <td class="text-start">{{ $item->name }}</td>
                        <td class="text-start">{{ $item?->company_staff?->contact_number }}</td>
                        <td class="text-start">{{ $item?->company_staff?->email }}</td>
                        <td class="text-start">{{ $item?->gst_no != null ? $item?->gst_no : '-' }}</td> 
                        <td class="text-start"><span class="{{ $item->status == 1 ? 'text-success' : 'text-danger' }}">
                                     {{ $item->status == 1 ? 'Active' : 'Inactive' }}
                            </span></td>
                        <td>
                            <x-panel::table.action.main>
                                <x-panel::table.action.edit :route="route('admin.company.edit',$item->id)" module="{{__('admin.company')}}" />
                                <a x-on:click="showAlert({{$item->id }})"><i class="fa-solid fa-trash icon text-danger"></i></a>
                               {{-- <x-panel::table.action.view :route="route('admin.company.details',$item->id)" module="{{__('admin.company')}}" />--}}
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