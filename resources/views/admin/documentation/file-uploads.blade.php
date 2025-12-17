<x-layouts.admin title="File Uploads - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- With Golden Retriever -->
            @php
                $code0 = '<x-file-upload 
    id="golden-retriever-upload"
    label="Upload with auto-restore"
    golden
    upload-route="{{ route("admin.files.upload") }}"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Golden Retriever</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code0 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <strong>Golden Retriever Özelliği:</strong> Bu plugin, sayfa yenilendiğinde veya tarayıcı kapandığında seçilen dosyaları otomatik olarak geri yükler. LocalStorage veya IndexedDB kullanarak dosya bilgilerini saklar. Kullanıcı yanlışlıkla sayfayı yenilese bile dosyalar kaybolmaz ve upload işlemi kaldığı yerden devam edebilir.
                </p>
                <x-file-upload 
                    id="golden-retriever-upload"
                    label="Upload with auto-restore"
                    golden
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Basic File Upload -->
            @php
                $code1 = '<x-file-upload 
    label="Upload files"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic File Upload</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code1 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload
                    label="Upload files"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Single File Upload -->
            @php
                $code2 = '<x-file-upload 
    label="Upload profile picture"
    accepted-file-types="image/*"
    :max-number-of-files="1"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Single File Upload</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code2 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload
                    label="Upload profile picture"
                    accepted-file-types="image/*"
                    max-number-of-files="1"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Multiple Files with Restrictions -->
            @php
                $code3 = '<x-file-upload 
    multiple 
    label="Upload images"
    accepted-file-types="image/jpeg,image/png"
    :max-file-size="5"
    :max-number-of-files="3"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Multiple Files with Restrictions</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code3 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    multiple 
                    label="Upload images"
                    accepted-file-types="image/jpeg,image/png"
                    :max-file-size="5"
                    :max-number-of-files="3"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Modal Mode -->
            @php
                $code4 = '<x-file-upload 
    label="Upload files (Modal)"
    :inline="false"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Modal Mode</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code4 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Upload files (Modal)"
                    :inline="false"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- With Image Editor -->
            @php
                $code5 = '<x-file-upload 
    multiple 
    label="Upload images with editor"
    accepted-file-types="image/*"
    editor
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Image Editor</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code5 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    multiple 
                    label="Upload images with editor"
                    accepted-file-types="image/*"
                    editor
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Disabled -->
            @php
                $code6 = '<x-file-upload 
    label="Upload files"
    disabled
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Disabled</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code6 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Upload files"
                    disabled
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Auto Proceed (Auto Upload) -->
            @php
                $code6a = '<x-file-upload 
    label="Auto upload (files upload immediately when selected)"
    auto-proceed
    upload-route="{{ route("admin.files.upload") }}"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Auto Proceed (Auto Upload)</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code6a }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <strong>Not:</strong> Bu örnekte <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">auto-proceed</code> prop'u aktif. Dosyalar seçilir seçilmez otomatik olarak yüklenmeye başlamalı. Yükleme butonuna tıklamaya gerek yok.
                </p>
                <x-file-upload 
                    label="Auto upload (files upload immediately when selected)"
                    auto-proceed
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Small Size -->
            @php
                $code7 = '<x-file-upload 
    label="Small upload area"
    :width="400"
    :height="300"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Small Size</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code7 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Small upload area"
                    :width="400"
                    :height="300"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Medium Size (Default) -->
            @php
                $code8 = '<x-file-upload 
    label="Medium upload area (default)"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Medium Size (Default)</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code8 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Medium upload area (default)"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- Large Size -->
            @php
                $code9 = '<x-file-upload 
    label="Large upload area"
    :width="800"
    :height="600"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Large Size</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code9 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Large upload area"
                    :width="800"
                    :height="600"
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- With Webcam -->
            @php
                $code12 = '<x-file-upload 
    label="Upload with webcam"
    webcam
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Webcam</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code12 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Upload with webcam"
                    webcam
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- With Screen Capture -->
            @php
                $code13 = '<x-file-upload 
    label="Upload with screen capture"
    screenshot
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Screen Capture</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code13 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Upload with screen capture"
                    screenshot
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- With Compressor -->
            @php
                $code14 = '<x-file-upload 
    label="Upload with compression"
    compress
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Compressor</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code14 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Upload with compression"
                    compress
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>

            <!-- With Thumbnail Generator -->
            @php
                $code15 = '<x-file-upload 
    label="Upload with thumbnails"
    thumbnails
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Thumbnail Generator</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code15 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-file-upload 
                    label="Upload with thumbnails"
                    thumbnails
                    upload-route="{{ route('admin.files.upload') }}"
                />
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-semibold mb-4">File Upload Component</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    The File Upload component uses Uppy.js library for advanced file upload functionality with previews, progress tracking, and more.
                </p>

                <h3 class="text-xl font-semibold mb-3 mt-6">Usage</h3>
                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-6"><code>&lt;x-file-upload 
    label="Upload files"
    multiple
    accepted-file-types="image/*"
    :max-file-size="5"
/&gt;</code></pre>

                <h3 class="text-xl font-semibold mb-3 mt-6">Props</h3>
                <ul class="space-y-3">
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Label text displayed above the upload area</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">multiple</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Allow multiple file selection (default: false)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input name attribute (auto-generated if not provided)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input ID attribute (auto-generated if not provided)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">accepted-file-types</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Comma-separated list of MIME types (e.g., "image/jpeg,image/png")</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">max-file-size</code>
                        <span class="text-gray-600 dark:text-gray-400"> - int</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Maximum file size in MB</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">max-number-of-files</code>
                        <span class="text-gray-600 dark:text-gray-400"> - int</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Maximum number of files allowed</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">inline</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Render Dashboard inline or as modal (default: true)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">width</code>
                        <span class="text-gray-600 dark:text-gray-400"> - int</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Width of the Dashboard in pixels (default: 600, only used when inline: true)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">height</code>
                        <span class="text-gray-600 dark:text-gray-400"> - int</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Height of the Dashboard in pixels (default: 400, only used when inline: true)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">theme</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Theme: "light", "dark", or "auto" (default: "auto")</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">editor</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Enable image editor for cropping, rotating, and editing images (default: false). Alias: <code class="text-xs">allow-image-editor</code></p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">note</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Custom note text to display below the upload area</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">auto-proceed</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Automatically start uploading when files are selected (default: false)</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">meta-fields</code>
                        <span class="text-gray-600 dark:text-gray-400"> - array</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Custom meta fields (e.g., [{id: 'caption', name: 'Caption', placeholder: 'Enter caption'}])</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Disable the file upload component</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">upload-route</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Server route URL for file upload</p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">webcam</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Enable webcam for taking photos (default: false). Alias: <code class="text-xs">allow-webcam</code></p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">screenshot</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Enable screen capture (default: false). Alias: <code class="text-xs">allow-screen-capture</code></p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">compress</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Enable automatic file compression (default: false). Alias: <code class="text-xs">enable-compressor</code></p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">golden</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Enable automatic file restoration after page reload. Stores file information in LocalStorage/IndexedDB and restores them when the page is refreshed or browser is closed/reopened. This prevents data loss if user accidentally refreshes the page. (default: false). Alias: <code class="text-xs">enable-golden-retriever</code></p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">thumbnails</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Enable automatic thumbnail generation for files (default: false). Alias: <code class="text-xs">enable-thumbnail-generator</code></p>
                    </li>
                    <li>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">form-selector</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">CSS selector for form integration (uploads files when form is submitted)</p>
                    </li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2">
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ File upload with preview</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Image preview support</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ File type and size validation</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Progress tracking</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Server upload with custom routes and headers</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Multiple file support</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Modal and inline modes</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Dark mode support</p>
                    </li>
                    <li>
                        <p class="text-xs text-gray-600 dark:text-gray-400">✅ Image editor with crop, rotate, zoom, and flip</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</x-layouts.admin>

