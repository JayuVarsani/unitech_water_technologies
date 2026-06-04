<div class="d-flex flex-column flex-lg-row flex-column-fluid">
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
        <div class="d-flex flex-center flex-column flex-lg-row-fluid">
            <div class="w-lg-500px p-10">
                <form class="form w-100" wire:submit="auth">
                    <div class="text-center mb-11">
                        <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                        <div class="text-gray-500 fw-semibold fs-6">{{ config('app.name') }} </div>
                    </div>
                    <x-panel::loader target="auth" />
                    <x-panel::alert />
                    <div class="fv-row mb-8">
                        <input type="text" placeholder="Mobile Number" name="contactNumber" autocomplete="off"
                               wire:model.blur="contactNumber"
                               class="form-control bg-transparent" />
                        <x-panel::error name="contactNumber" />
                    </div>

                    <div class="fv-row mb-3">
                        <div class="input-group mt-3" x-data="{showPassword:false}">
                            <input x-bind:type="showPassword?'text':'password'" type="password" placeholder="Password" name="password" autocomplete="off"
                                   wire:model.blur="password"
                                   class="form-control bg-transparent">
                            <span class="input-group-text cursor-pointer" x-on:click="showPassword=!showPassword;">
                                <i class="fa" x-bind:class="showPassword?'fa-eye':'fa-eye-slash'"></i>
                            </span>
                        </div>
                        <x-panel::error name="password" />
                    </div>
                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                        <div></div>
                        <a href="{{route('company.auth.forgot-password')}}" wire:navigate class="link-primary">
                            {{__('company.forgot-password.title')}}?
                        </a>
                    </div>
                    <div class="d-grid mb-10">
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                            {{__('company.login.sign_in')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@script
<script>
$(document).ready(function () {
    function initializeInputNavigation() {
        document.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                const form = event.target.form;
                if (form) {
                    event.preventDefault();
                    const focusableElements = Array.from(
                        form.querySelectorAll(
                            'input, textarea, select, [tabindex]:not([tabindex="-1"])'
                        )
                    );

                    // Handle Select2-specific logic
                    if ($(event.target).hasClass("select2-search__field")) {
                        const select2Element = $(event.target)
                            .closest(".select2-container")
                            .prev("select.add-select2");
                        if (select2Element.length) {
                            select2Element.select2("close");
                            moveToNextElement(form, focusableElements, select2Element[0]);
                            return;
                        }
                    }

                    moveToNextElement(form, focusableElements, event.target);
                }
            }
        });
    }

    function moveToNextElement(form, focusableElements, currentElement) {
        const currentIndex = focusableElements.indexOf(currentElement);
        if (currentIndex === focusableElements.length - 1) {
            form.requestSubmit(); // Submit the form if it's the last element
        } else {
            let nextElement = focusableElements[currentIndex + 1];

            // Skip the Select2 span wrapper if nextElement is not the select itself
            if ($(nextElement).hasClass('select2-selection') || $(nextElement).hasClass('select2-selection--single')) {
                nextElement = focusableElements[currentIndex + 2] || nextElement;
            }

            // Check if the next element is a Select2 element
            if ($(nextElement).hasClass("add-select2")) {
                if ($.fn.select2 && $(nextElement).data("select2")) {
                    console.log("Opening Select2 dropdown for:", nextElement);
                    $(nextElement).select2("open");
                } else {
                    console.error("Select2 not initialized on the next element:", nextElement);
                }
            } else {
                console.log("Focusing on next element:", nextElement);
                nextElement.focus();
            }
        }
    }

    function initializeSelect2() {
        $(".add-select2").each(function () {
            if (!$(this).data("select2")) {
                console.log("Initializing Select2 for element:", this);
                $(this).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                });
            }
        });

        $(".add-select2").on("select2:close", function (e) {
            const form = e.target.form;
            const focusableElements = Array.from(
                form.querySelectorAll(
                    'input, textarea, select, [tabindex]:not([tabindex="-1"])'
                )
            );
                     var selectedUnitId = $('#unit_id option:selected').val();
                    var selectedCategoryId = $('#category_id option:selected').val();
                    @this.set('form.unitId', selectedUnitId);
                    @this.set('form.categoryId', selectedCategoryId);
                    @this.set('form.materialId', $(this).val());
                    $('#materialId').select2().on('change', function () {
                     @this.set('form.materialId', $(this).val());
                    });
            moveToNextElement(form, focusableElements, e.target);
        });
    }

    initializeInputNavigation();
    initializeSelect2();

    Livewire.hook("message.processed", () => {
        console.log("Livewire processed, reinitializing Select2.");
        initializeSelect2();
    });
    });
</script>
@endscript