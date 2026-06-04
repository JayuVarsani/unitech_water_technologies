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
                            <input type="text" id="material_name" maxlength="30" name="material_name" wire:model="form.material_name"
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

// $(document).ready(function () {
//     function initializeInputNavigation() {
//         document.addEventListener("keydown", (event) => {
//             if (event.key === "Enter") {
//                 const form = event.target.form;
//                 if (form) {
//                     event.preventDefault();
//                     const focusableElements = Array.from(
//                         form.querySelectorAll(
//                             'input, textarea, select, [tabindex]:not([tabindex="-1"])'
//                         )
//                     );

//                     // Handle Select2-specific logic
//                     if ($(event.target).hasClass("select2-search__field")) {
//                         const select2Element = $(event.target)
//                             .closest(".select2-container")
//                             .prev("select.add-select2");
//                         if (select2Element.length) {
//                             select2Element.select2("close");
//                             moveToNextElement(form, focusableElements, select2Element[0]);
//                             return;
//                         }
//                     }

//                     moveToNextElement(form, focusableElements, event.target);
//                 }
//             }
//         });
//     }

//     function moveToNextElement(form, focusableElements, currentElement) {
//         const currentIndex = focusableElements.indexOf(currentElement);
//         if (currentIndex === focusableElements.length - 1) {
//             form.requestSubmit(); // Submit the form if it's the last element
//         } else {
//             let nextElement = focusableElements[currentIndex + 1];

//             // Skip the Select2 span wrapper if nextElement is not the select itself
//             if ($(nextElement).hasClass('select2-selection') || $(nextElement).hasClass('select2-selection--single')) {
//                 nextElement = focusableElements[currentIndex + 2] || nextElement;
//             }

//             // Check if the next element is a Select2 element
//             if ($(nextElement).hasClass("add-select2")) {
//                 if ($.fn.select2 && $(nextElement).data("select2")) {
//                     console.log("Opening Select2 dropdown for:", nextElement);
//                     $(nextElement).select2("open");
//                 } else {
//                     console.error("Select2 not initialized on the next element:", nextElement);
//                 }
//             } else {
//                 console.log("Focusing on next element:", nextElement);
//                 nextElement.focus();
//             }
//         }
//     }

//     function initializeSelect2() {
//         $(".add-select2").each(function () {
//             if (!$(this).data("select2")) {
//                 console.log("Initializing Select2 for element:", this);
//                 $(this).select2({
//                     placeholder: "Select an option",
//                     allowClear: true,
//                 });
//             }
//         });

//         $(".add-select2").on("select2:close", function (e) {
//             const form = e.target.form;
//             const focusableElements = Array.from(
//                 form.querySelectorAll(
//                     'input, textarea, select, [tabindex]:not([tabindex="-1"])'
//                 )
//             );
//                  var selectedUnitId = $('#unit_id').val();
//                  @this.set('form.unit_id', selectedUnitId);
//             moveToNextElement(form, focusableElements, e.target);
//         });
//     }

//     initializeInputNavigation();
//     initializeSelect2();

//     Livewire.hook("message.processed", () => {
//         console.log("Livewire processed, reinitializing Select2.");
//         initializeSelect2();
//     });
//     });


</script>
@endscript
