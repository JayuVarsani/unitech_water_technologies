@props(['name', 'form', 'prevImage' => null])
<div x-data="imageCropper()" class="space-y-4">
    <div style="background-color: #F8FBFF;border: 1px solid white;box-shadow: var(--bs-box-shadow);">
        <div class="d-flex justify-content-center align-items-center" style="cursor: pointer;height: 100px">
            <!-- File Input -->
            <input type="file" accept="image/*" class="d-none" x-ref="fileInput" @change="loadImage">

            <!-- Preview: crop area (always in DOM so x-ref="image" exists on second upload) -->
            <div x-show="imageUrl" class="max-w-md d-flex justify-content-center" x-cloak>
                <img x-ref="image" :src="imageUrl" style="height: 100px; max-width: 100%;">
            </div>

            <div x-show="!imageUrl && !isImageLoading" class="max-w-md">
                @if ($form->getPropertyValue($name) || $prevImage)
                    <img class="w-100" style="height: 100px" x-show="!imageUrl" x-cloak
                        src="{{ $form->{$name}?->temporaryUrl() ?? $prevImage }}">
                @endif
                @if (!$form->getPropertyValue($name) && !$prevImage)
                    <i class="fa-solid fa-upload fa-4x" style="font-size: 40px" x-cloak></i>
                @endif
            </div>
        </div>
        <div class="btn-group btn-group-sm w-100" x-show="!isUploading" x-cloak>
            <button x-cloak type="button" class="btn btn-info" x-on:click="triggerUpload" :disabled="isUploading">
                <i class="fa-solid fa-upload"></i>
                Browse
            </button>
            <button type="button" x-show="imageUrl" x-cloak class="btn btn-success" x-on:click="handleSubmit"
                :disabled="isUploading">
                <i class="fa-solid fa-crop"></i>
                <span x-show="!isUploading">Save</span>
                <span x-show="isUploading">Uploading...</span>
            </button>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('imageCropper', () => {
            return {
                imageUrl: null,
                cropper: null,
                isUploading: false,
                isImageLoading: false,

                triggerUpload() {
                    // If already selected image → cleanup first
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }

                    this.imageUrl = null;

                    this.$refs.fileInput.value = null;

                    this.$refs.fileInput.click();
                },
                loadImage(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    this.isImageLoading = true; // start spinner

                    const reader = new FileReader();

                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                        this.$nextTick(() => {
                            if (this.cropper) this.cropper.destroy();

                            this.cropper = new Cropper(this.$refs.image, {
                                viewMode: 1,
                                dragMode: 'move',
                                aspectRatio: NaN, // free crop
                                autoCropArea: 1,
                                responsive: true,
                                zoomable: true,
                                scalable: false,
                                ready: () => {
                                    this.isImageLoading = false;
                                }
                            });
                        });
                    };

                    reader.readAsDataURL(file);
                },

                handleSubmit() {
                    if (!this.cropper) {
                        return;
                    }

                    this.isUploading = true;

                    const canvas = this.cropper.getCroppedCanvas({
                        maxWidth: 1200,
                        maxHeight: 1200,
                    });
                    canvas.toBlob((blob) => {

                        const file = new File([blob], 'cropped.jpg', {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });

                        // Upload to Livewire manually
                        @this.upload(
                            'form.{{ $name }}',
                            file,
                            () => {
                                // success
                                if (this.cropper) {
                                    this.cropper.destroy();
                                    this.cropper = null;
                                }
                                this.isUploading = false;
                                this.imageUrl = null;
                            },
                            () => {
                                // error
                                this.isUploading = false;
                            }
                        );
                    }, 'image/jpeg', 0.50); // compression quality
                }
            }
        });
    </script>
@endscript
