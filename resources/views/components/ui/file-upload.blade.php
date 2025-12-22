@props([
    'name',
    'label' => 'Cover Picture',
    'value' => null,
    'accept' => 'image/png, image/jpeg, image/webp',
    'helperText' => 'PNG, JPG, WebP - Max 5MB',
])

<div class="w-full" x-data="{ 
    preview: @js($value ? asset('storage/' . $value) : null),
    isDragging: false,
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewFile(file);
        }
    },
    handleDrop(event) {
        this.isDragging = false;
        const file = event.dataTransfer.files[0];
        if (file) {
            this.previewFile(file);
            // Manually assign files to input for form submission
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            this.$refs.fileInput.files = dataTransfer.files;
        }
    },
    previewFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => this.preview = e.target.result;
        reader.readAsDataURL(file);
    },
    removeFile() {
        this.preview = null;
        this.$refs.fileInput.value = '';
        // If there's a hidden input for removing the image (handled in parent usually, but we can emit or handle here if needed)
        // For now, clearing the input is the main UI action. 
        // If we need to signal removal to backend, we might need a hidden input 'remove_image'
    }
}">
    <div class="flex flex-col gap-1">
        @if($label)
            <span class="w-fit pl-0.5 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $label }}</span>
        @endif
        
        <div 
            class="relative flex w-full flex-col items-center justify-center gap-2 rounded-lg border border-dashed p-8 transition-colors"
            x-bind:class="{
                'border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800/50': !isDragging,
                'border-[var(--color-primary)] bg-[var(--color-primary)]/5': isDragging
            }"
            x-on:dragover.prevent="isDragging = true"
            x-on:dragleave.prevent="isDragging = false"
            x-on:drop.prevent="handleDrop($event)"
        >
            <!-- Preview Image -->
            <div x-show="preview" x-cloak class="absolute inset-0 z-10 flex items-center justify-center bg-zinc-50 dark:bg-zinc-900 rounded-lg overflow-hidden">
                <img :src="preview" class="h-full w-full object-cover opacity-50" />
                <div class="absolute inset-0 flex items-center justify-center gap-2">
                    <button 
                        type="button" 
                        x-on:click="removeFile()"
                        class="rounded-full bg-red-500 p-2 text-white hover:bg-red-600 transition-colors shadow-sm"
                        title="Remove image"
                    >
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <label 
                        for="{{ $name }}_input" 
                        class="cursor-pointer rounded-full bg-zinc-900/75 p-2 text-white hover:bg-zinc-900 transition-colors shadow-sm"
                        title="Change image"
                    >
                        <i class="fas fa-pen"></i>
                    </label>
                </div>
            </div>

            <!-- Upload Placeholder -->
            <div class="flex flex-col items-center justify-center gap-2 text-zinc-600 dark:text-zinc-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor" class="w-12 h-12 opacity-75">
                    <path fill-rule="evenodd" d="M10.5 3.75a6 6 0 0 0-5.98 6.496A5.25 5.25 0 0 0 6.75 20.25H18a4.5 4.5 0 0 0 2.206-8.423 3.75 3.75 0 0 0-4.133-4.303A6.001 6.001 0 0 0 10.5 3.75Zm2.03 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v4.94a.75.75 0 0 0 1.5 0v-4.94l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd"/>
                </svg>
                <div class="group text-center">
                    <label for="{{ $name }}_input" class="font-medium text-[var(--color-primary)] group-focus-within:underline cursor-pointer hover:text-[var(--color-primary-dark)]">
                        Browse
                    </label>
                    <span>or drag and drop here</span>
                </div>
                <small class="text-xs">{{ $helperText }}</small>
            </div>

            <input 
                id="{{ $name }}_input" 
                type="file" 
                name="{{ $name }}" 
                class="sr-only" 
                accept="{{ $accept }}"
                x-ref="fileInput"
                x-on:change="handleFileSelect($event)"
            />
        </div>
    </div>
</div>
