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
                           placeholder="Please enter name">
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
                            <tr class="mx-auto">
                                <td class="text-capitalize">{{$module['name']}}</td>
                                <td class="p-3">
                                    <select class="form-control select_2_el" multiple
                                            data-model-name={{$module['unique_name']}}
                                            wire:model="permission.{{$module['unique_name']}}">
                                        @foreach($module['actions'] as $action)
                                            <option value="{{$action}}">
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
<script defer>
    $(".select_2_el").select2({
        placeholder: "Select Actions"
    }).on("change", function(e) {
        const modelName = $(e.target).data("modelName");
        @this.
        set(`permission.${modelName}`, $(e.target).select2("val"));
    });
</script>
@endscript

