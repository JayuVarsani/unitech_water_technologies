<div class="card mb-5 mb-xl-10">
    <x-panel::loader target="createRole" />
    <x-panel::alert />
    <!-- <div class="card-header border-0">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">{{$title}}</h3>
        </div>
    </div> -->
    <form class="form" id="main_form" method="post" wire:submit="createRole" enctype="multipart/form-data">
        @csrf
        <div class="card-body border-top p-9">
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold fs-6 required"
                       for="name">{{__('admin.input.name')}}</label>
                <div class="col-lg-9 fv-row">
                    <input type="text"
                           name="name" id="name"
                           onblur="this.value = this.value.trim()"
                           class="form-control form-control-lg form-control-solid"
                           wire:model.blur="name"
                           placeholder="Please enter name" 
                           @if(in_array($name, ['Machine operator', 'Designer','Accountant'])) readonly @endif>
                    <x-panel::error name="name" />
                </div>
            </div>
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold fs-6 required" for="name">Permissions</label>
                <div class="col-lg-9">
                    <table class="table table-bordered table-striped panel_table">
                        <thead>
                        <tr>
                            <th scope="col">Module</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody wire:ignore>
                        @foreach($modules as $module)
                            @php
                                $moduleName = $module['unique_name'];
                                $moduleName = explode('.',$moduleName);
                                $moduleName = $moduleName[1];
                            @endphp
                            
                            <tr class="mx-auto">
                                <td class="text-capitalize">{{ $module['name'] }}</td>
                                <td class="p-3 ">
                                    <select class="form-control select_2_el"
                                        data-model-name="{{ $moduleName }}"
                                        data-selected="{{ json_encode($permission['company'][$moduleName] ?? []) }}"

                                        wire:model="permission.company{{ $moduleName }}"
                                        multiple @if($name === 'Machine operator' && $module['name'] === 'Job Cards') disabled style="pointer-events: none; opacity: 0.6;" @endif>
                                        @foreach($module['actions'] as $action)
                                            <option value="{{$action}}"  
                                            
                                            @if(in_array($action, $permission['company'][$moduleName] ?? [])) selected @endif >
                                            
                                                {{ucwords(str_replace('_',' ',$action))}}
                                                
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{route('company.staff-management.role-permission.index')}}"
               class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
            <button type="submit" class="btn btn-primary">{{__('app.panel.submit')}}</button>
        </div>
    </form>
</div>
@script
<script>
   

    $('.select_2_el').select2({
        placeholder: 'Select actions',
        width: '100%',
        closeOnSelect: true, // Prevents closing dropdown when an item is deselected
            allowClear: true, // Enables clear functionality
            multiple: true
    }).on('change', function (e) {
        let modelName = $(this).data('model-name');
        let selectedData = $(this).data('selected');

        @this.set(`permission.company.${modelName}`, $(this).val());
        
    });
    
    $(this).val(@json($permission['company'] ?? []));
    $(this).trigger('change');



// Proper unselect handling

// $('.select_2_el').on('select2:unselect', function (e) {
   
//     let modelName = $(this).data('model-name');
//     let removedValue = e.params.data.id; // Get the value that was unselected
//      let selectedValues = $(this).data('selected'); // Get the updated selected values
//     // let selectedValues = $(this).val() || [];

//     console.log('Unselect Triggered for:', modelName);
//     console.log('Removed Option:', removedValue);
//     console.log('Updated Permissions (Before Removal):', selectedValues);

//     // Remove ONLY the unselected value from the array manually
//      selectedValues = selectedValues.filter(value => value !== removedValue);

//     // console.log('Updated Permissions (After Removal):', selectedValues);

//     // Manually set the new selected values
//     $(this).val(selectedValues).trigger('change');

//     // Emit event to Livewire with the updated values
//     //  Livewire.dispatch('updatePermissions', modelName, selectedValues);
//       console.log('Updated Permissions (After Removal):', selectedValues);
// });


let selectedPermissions = {}; // Store selected values for each model
let preventOpen = false; // Prevent dropdown from opening after unselect

$('.select_2_el').each(function () {
    let modelName = $(this).data('model-name');
    selectedPermissions[modelName] = $(this).val() || []; // Initialize with selected values
});

// Prevent dropdown opening after unselect
$('.select_2_el').on('select2:opening', function (e) {
    if (preventOpen) {
        e.preventDefault(); // Stop dropdown from opening
        preventOpen = false; // Reset flag after preventing once
    }
});

$('.select_2_el').on('select2:unselect', function (e) {
    let modelName = $(this).data('model-name');
    let removedValue = e.params.data.id; // Get the value that was unselected

    if (!selectedPermissions[modelName]) {
        selectedPermissions[modelName] = []; // Ensure array exists
    }

    console.log('Unselect Triggered for:', modelName);
    console.log('Removed Option:', removedValue);
    console.log('Updated Permissions (Before Removal):', selectedPermissions[modelName]);

    // Remove ONLY the unselected value from the stored array
    selectedPermissions[modelName] = selectedPermissions[modelName].filter(value => value !== removedValue);

    console.log('Updated Permissions (After Removal):', selectedPermissions[modelName]);

    // Prevent dropdown from opening
    preventOpen = true;

    // Update the Select2 element manually
    $(this).val(selectedPermissions[modelName]).trigger('change');

    
});




    
    function initializeSelect2()
    {

        $('.select_2_el').select2({
            placeholder: 'Select actions',
            width: '100%',
            closeOnSelect: false, // Prevents closing dropdown when an item is deselected
            allowClear: true, // Enables clear functionality
            multiple: true
        });

       

    }



    document.addEventListener('livewire:load', function () {
        initializeSelect2();
        Livewire.hook('message.processed', (message, component) => {
            initializeSelect2();
        });
    });

</script>
@endscript

