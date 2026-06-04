<div>
    <x-panel::alert/>
    <div class="card mb-5 mb-xl-10">
        <x-panel::loader target="save"/>
        <!-- <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{$title}}</h3>
            </div>
        </div> -->
        <form class="form" method="post" wire:submit="save" enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top p-6">




            <div class="row mb-6">

                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="materialId">{{ __('company.material')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  id="materialId" wire:model.defer="form.materialId">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.material')]) }}</option>
                                @foreach($material as $single_material)
                                    <option value="{{ $single_material->id }}">{{$single_material->material_name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <x-panel::error name="form.materialId"/>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="materialId"></label>
                        <div wire:ignore class="col-lg-12 fv-row">
                           
                        <button type="button" wire:click="addMaterial" class="btn btn-primary btn-rounded" style="margin-top:14px;"><i class="fa-solid fa-plus"></i> </button>
                        </div>
                       
                    </div>
            </div>
                <div class="row mb-6">
                <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 no-footer">
                                            <thead>
                                            <tr class="text-start text-muted fw-bold fs-4 text-uppercase gs-0">
                                            </tr>
                                            </thead>
                                            <tbody class="text-gray-800">
                                        
                                                @foreach($selectedMaterials as $selectedMaterial)
                                                    <tr>
                                                    <td>{{ $selectedMaterial['name'] }}</td>       
                                                    <td>
                                                        
                                                    <a style="cursor: pointer" wire:click="removeMaterial({{ $selectedMaterial['id'] }})" data-toggle="tooltip" data-placement="top" >
                                                        <i class="fa-solid fa-trash icon text-danger"></i>
                                                    </a>
                                                            
                                                    </td>
                                                </tr>
                                                @endforeach
                                    
                                            </tbody>
                                    </table>
                
                                </div>
                                </div>   
                                           
                </div>







                <div class="row mb-6">
                    <div class="col-md-6">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.name')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="name"  wire:model.blur="form.name"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.name')]) }}">
                            <x-panel::error name="form.name"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.product_unit')}}</label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  wire:model.defer="form.unitId" id="unit_id">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.product_unit')]) }}</option>
                                @foreach($productUnits as $unit)
                                    <option value="{{ $unit->id }}">{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.unitId"/>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="price">{{ __('company.input.rate')}} (₹)</label>
                        <div class="col-lg-12 fv-row">
                            <input type="number" step="any" id="price" maxlength="8" name="price" wire:model.blur="form.price"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.rate')]) }}">
                            <x-panel::error name="form.price"/>
                        </div>
                    </div>
                    
                   {{--<div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="stock">{{ __('company.input.stock')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="stock" maxlength="10" name="stock" wire:model.blur="form.stock"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.stock')]) }}">
                            <x-panel::error name="form.stock"/>
                        </div>
                    </div>--}}
                </div>
                <div class="row mb-6">
               
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.category')}}
                        @if($canCreateCategory)
                        <a href='#' wire:click.prevent="storeToSession" class="m-input-icon__icon m-input-icon__icon--right"> Add New</a>
                        @endif
                        </label>
                        <div wire:ignore class="col-lg-12 fv-row">
                            <select class="form-select form-select-lg add-select2"  id="category_id" wire:model.defer="form.categoryId">
                                <option value="">{{ __('company.placeholder.select', ['name' => __('company.input.category')]) }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-panel::error name="form.categoryId"/>
                    </div>
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.product_id')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="productId" maxlength="20" name="productId" wire:model.blur="form.productId"
                                    class="form-control form-control-lg form-control-solid text-uppercase"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.product_id')]) }}">
                            <x-panel::error name="form.productId"/>
                        </div>
                    </div>
                    {{--<div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6 required" for="name">{{ __('company.input.sku')}}</label>
                        <div class="col-lg-12 fv-row">
                            <input type="text" id="sku" maxlength="30" name="sku" wire:model.blur="form.sku"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.sku')]) }}">
                            <x-panel::error name="form.sku"/>
                        </div>
                    </div>--}}
                    <div class="col-md-3">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6" for="minimum_price">{{ __('company.input.minprice')}} (₹)</label>
                        <div class="col-lg-12 fv-row">
                            <input type="number" id="minimum_price" maxlength="8" name="minimum_price" wire:model.blur="form.minimum_price"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="{{ __('company.placeholder.enter', ['name' => __('company.input.minprice')]) }}">
                            <x-panel::error name="form.minimum_price"/>
                        </div>
                    </div>
                   
                    
                    

                </div>
            
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('company.product.index') }}"
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

        $(".add-select2").on("select2:change", function (e) {
        let materialId = $(this).val();
        @this.set('form.materialId', materialId);
    });

    $('#materialId').on('change', function () {
        @this.set('form.materialId', $(this).val());
    });

    $('#unit_id').on('change', function () {
        var selectedUnitId = $(this).val();
        @this.set('form.unitId', selectedUnitId);
    });

    $('#category_id').on('change', function () {
        var selectedCategoryId = $(this).val();
        @this.set('form.categoryId', selectedCategoryId);
    });

        



    });


   
</script>
@endscript
