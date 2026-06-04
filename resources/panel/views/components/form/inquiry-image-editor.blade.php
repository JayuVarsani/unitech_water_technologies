@props(['name', 'form', 'prevImage' => null, 'isEdit' => false])
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
                @if ($isEdit)
                    @if ($form->{$name})
                        @if (!str_starts_with($form->{$name}, 'http'))
                            <img src="{{ $form->{$name}?->temporaryUrl() ?? asset('build/panel/images/thumbnail.jpg') }}"
                                x-show="!imageUrl" x-cloak style="height: 100px;width:100px;" alt="Job Image" />
                        @else
                            <img src="{{ $form->{$name} ?? asset('build/panel/images/thumbnail.jpg') }}"
                                x-show="!imageUrl" x-cloak style="height: 100px;width:100px;" alt="Job Image" />
                        @endif
                    @else
                        <i class="fa-solid fa-upload fa-4x" style="font-size: 40px" x-cloak></i>
                    @endif
                @else
                    @if ($form->getPropertyValue($name) || $prevImage)
                        <img class="w-100" style="height: 100px" x-show="!imageUrl" x-cloak
                            src="{{ $form->{$name}?->temporaryUrl() ?? $prevImage }}">
                    @endif
                    @if (!$form->getPropertyValue($name) && !$prevImage)
                        <i class="fa-solid fa-upload fa-4x" style="font-size: 40px" x-cloak></i>
                    @endif
                @endif
            </div>
        </div>
        <div class="btn-group btn-group-sm w-100" x-show="!isUploading" x-cloak>
            <button x-cloak type="button" class="btn btn-info" x-on:click="triggerUpload" :disabled="isUploading">
                <i class="fa-solid fa-upload"></i>
                {{ __('app.panel.browse') }}
            </button>
            <button type="button" x-show="imageUrl" x-cloak class="btn btn-success" x-on:click="handleSubmit"
                :disabled="isUploading">
                <i class="fa-solid fa-crop"></i>
                <span x-show="!isUploading">{{ __('app.panel.save') }}</span>
                <span x-show="isUploading">{{ __('app.panel.uploading') }}</span>
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

                init() {
                    window.addEventListener('validate-image-editor', async (event) => {
                        const {
                            type
                        } = event.detail;
                        try {
                            // If image selected → upload first
                            if (this.cropper || this.imageUrl) {
                                await this.handleSubmit();
                            }
                            // Then call Livewire method
                            if (type == 'addJob') {
                                this.$wire.addJob();
                            } else {
                                this.$wire.updateJob();
                            }

                        } catch (e) {
                            console.log('Upload failed');
                        }
                    });
                },

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
                    return new Promise((resolve, reject) => {
                        if (!this.cropper) {
                            resolve();
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
                                    resolve();
                                },
                                () => {
                                    // error
                                    this.isUploading = false;
                                    reject();
                                }
                            );
                        }, 'image/jpeg', 0.50); // compression quality
                    });
                }
            }
        });
    </script>
@endscript
