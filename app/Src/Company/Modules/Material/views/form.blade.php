<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
       
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">
                <div class="row mb-6">
                    <div class="col-md-6">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="material_name">{{ __('company.input.material_name')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="material_name"  name="material_name" wire:model="form.material_name"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.material_name')]) }}">
                            <x-panel::error name="form.material_name"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="unit_id">{{ __('company.input.material_unit')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model="form.unit_id" id="unit_id">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.material_unit')]) }}</option>
                                @foreach($productUnits as $unit)
                                    <option value="{{ $unit->id }}">{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.unit_id"/>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="narration">{{ __('company.input.narration')}}</label>
                        <div class="col-lg-12 fv-row">
                            <textarea id="narration" maxlength="30" name="narration" wire:model="form.narration"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.narration')]) }}"></textarea>
                            <x-panel::error name="form.narration"/>
                        </div>
                    </div>
                   <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="available_stock">{{ __('company.input.stock')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="number" id="available_stock" maxlength="30"  wire:model="form.available_stock"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.stock')]) }}">
                            <x-panel::error name="form.available_stock"/>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                {{--<div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="materialId">{{ __('company.input.material_id')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="materialId" maxlength="30" name="materialId" wire:model="form.materialId"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.material_id')]) }}">
                            <x-panel::error name="form.materialId"/>
                        </div>
                    </div>--}}
                    
                    
                    {{--<div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="sku">{{ __('company.input.sku')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="sku" maxlength="30" name="sku" wire:model="form.sku"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.sku')]) }}">
                            <x-panel::error name="form.sku"/>
                        </div>
                    </div>--}}
                    


                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.material.index') }}"
                   class="btn btn-light btn-active-light-primary me-2">{{__('app.panel.cancel')}}</a>
                <button type="submit" class="btn btn-primary">{{__('app.panel.submit')}}</button>
            </div>
        </form>
    </div>
</div>
@script
<script>



    $(document).ready(function () {
        $('.add-select2').select2();
        $('.add-select2 option:first-child').prop('disabled', true);
        $('.add-select2').on('change', function (e) {
            var selectedUnitId = $('#unit_id').val();
            @this.set('form.unit_id', selectedUnitId);
           
        });

        // Open select2 only when clicked or arrow key is pressed
        $('#unit_id').on('keydown', function (e) {
            if (e.key === 'Enter') {
                $(this).select2('open'); // Open dropdown only on arrow key or enter
            }
        });





    });



</script>
@endscript
