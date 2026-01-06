@props([
    'name',
    'value' => '',
    'placeholder' => 'Start typing...',
    'label' => null,
    'required' => false,
    'id' => null,
])

@php
    $editorId = $id ?? 'editor-' . uniqid();
    $inputName = $name;
    $inputId = 'input-' . $editorId;
@endphp

<div>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div
        x-data="{
            ...setupEditor('{{ addslashes($value) }}', '{{ addslashes($placeholder) }}'),
            activeTab: 'editor',
            sourceCode: '',
            switchTab(tab) {
                if (tab === 'html' && this.activeTab === 'editor') {
                    // Switch to HTML: Get HTML from editor and format it
                    const html = this.getHTML();
                    this.sourceCode = this.formatHTML(html);
                } else if (tab === 'editor' && this.activeTab === 'html') {
                    // Switch to Editor: Save HTML back to editor (unformat first)
                    const unformatted = this.sourceCode.replace(/\n\s*/g, ' ').trim();
                    this.setHTML(unformatted);
                }
                this.activeTab = tab;
            }
        }"
        x-init="() => init($refs.editor)"
        class="tiptap-editor-wrapper"
    >
        <!-- Tabs -->
        <div 
            x-show="isLoaded()"
            class="flex items-center border border-zinc-200 dark:border-zinc-700 rounded-t-md bg-zinc-50 dark:bg-zinc-800/50"
        >
            <button
                type="button"
                @click="switchTab('editor')"
                :class="{ 
                    'bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100': activeTab === 'editor',
                    'hover:bg-zinc-100 dark:hover:bg-zinc-700/50': activeTab !== 'editor'
                }"
                class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 rounded-tl-md transition-colors"
            >
                <i class="fas fa-edit mr-2"></i>
                Editor
            </button>
            <button
                type="button"
                @click="switchTab('html')"
                :class="{ 
                    'bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100': activeTab === 'html',
                    'hover:bg-zinc-100 dark:hover:bg-zinc-700/50': activeTab !== 'html'
                }"
                class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 rounded-tr-md transition-colors"
            >
                <i class="fab fa-html5 mr-2"></i>
                HTML
            </button>
        </div>

        <!-- Toolbar (only show in editor tab) -->
        <div 
            x-show="isLoaded() && activeTab === 'editor'"
            class="flex flex-wrap items-center gap-1 p-2 border-l border-r border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50"
        >
            <!-- Undo/Redo -->
            <div class="flex items-center gap-1 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button
                    type="button"
                    @click="undo()"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Undo"
                >
                    <i class="fas fa-undo"></i>
                </button>
                <button
                    type="button"
                    @click="redo()"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Redo"
                >
                    <i class="fas fa-redo"></i>
                </button>
            </div>

            <!-- Heading Buttons -->
            <div class="flex items-center gap-1 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button
                    type="button"
                    @click="toggleHeading(1)"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('heading', { level: 1 }, updatedAt) }"
                    class="px-2 py-1 text-sm font-semibold rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Heading 1"
                >
                    H1
                </button>
                <button
                    type="button"
                    @click="toggleHeading(2)"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('heading', { level: 2 }, updatedAt) }"
                    class="px-2 py-1 text-sm font-semibold rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Heading 2"
                >
                    H2
                </button>
                <button
                    type="button"
                    @click="toggleHeading(3)"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('heading', { level: 3 }, updatedAt) }"
                    class="px-2 py-1 text-sm font-semibold rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Heading 3"
                >
                    H3
                </button>
            </div>

            <!-- Text Formatting -->
            <div class="flex items-center gap-1 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button
                    type="button"
                    @click="toggleBold()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('bold', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Bold"
                >
                    <i class="fas fa-bold"></i>
                </button>
                <button
                    type="button"
                    @click="toggleItalic()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('italic', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Italic"
                >
                    <i class="fas fa-italic"></i>
                </button>
                <button
                    type="button"
                    @click="toggleUnderline()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('underline', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Underline"
                >
                    <i class="fas fa-underline"></i>
                </button>
                <button
                    type="button"
                    @click="toggleStrike()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('strike', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Strikethrough"
                >
                    <i class="fas fa-strikethrough"></i>
                </button>
                <button
                    type="button"
                    @click="toggleCode()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('code', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Inline Code"
                >
                    <i class="fas fa-code"></i>
                </button>
                <button
                    type="button"
                    @click="toggleSubscript()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('subscript', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Subscript"
                >
                    <i class="fas fa-subscript"></i>
                </button>
                <button
                    type="button"
                    @click="toggleSuperscript()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('superscript', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Superscript"
                >
                    <i class="fas fa-superscript"></i>
                </button>
            </div>

            <!-- Lists -->
            <div class="flex items-center gap-1 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button
                    type="button"
                    @click="toggleBulletList()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('bulletList', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Bullet List"
                >
                    <i class="fas fa-list-ul"></i>
                </button>
                <button
                    type="button"
                    @click="toggleOrderedList()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('orderedList', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Numbered List"
                >
                    <i class="fas fa-list-ol"></i>
                </button>
                <button
                    type="button"
                    @click="toggleTaskList()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('taskList', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Task List (Checkbox)"
                >
                    <i class="fas fa-tasks"></i>
                </button>
            </div>

            <!-- Links -->
            <div class="flex items-center gap-1 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button
                    type="button"
                    @click="setLink()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('link', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Insert Link"
                >
                    <i class="fas fa-link"></i>
                </button>
                <button
                    type="button"
                    @click="unsetLink()"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Remove Link"
                >
                    <i class="fas fa-unlink"></i>
                </button>
            </div>

            <!-- Alignment -->
            <div class="flex items-center gap-1 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button
                    type="button"
                    @click="setTextAlign('left')"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'left' }, updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Align Left"
                >
                    <i class="fas fa-align-left"></i>
                </button>
                <button
                    type="button"
                    @click="setTextAlign('center')"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'center' }, updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Align Center"
                >
                    <i class="fas fa-align-center"></i>
                </button>
                <button
                    type="button"
                    @click="setTextAlign('right')"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'right' }, updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Align Right"
                >
                    <i class="fas fa-align-right"></i>
                </button>
                <button
                    type="button"
                    @click="setTextAlign('justify')"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'justify' }, updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Justify"
                >
                    <i class="fas fa-align-justify"></i>
                </button>
            </div>

            <!-- Other -->
            <div class="flex items-center gap-1">
                <button
                    type="button"
                    @click="toggleBlockquote()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('blockquote', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Blockquote"
                >
                    <i class="fas fa-quote-right"></i>
                </button>
                <button
                    type="button"
                    @click="setCodeBlock()"
                    :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('codeBlock', updatedAt) }"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Code Block"
                >
                    <i class="fas fa-terminal"></i>
                </button>
                <button
                    type="button"
                    @click="setHorizontalRule()"
                    class="px-2 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Horizontal Rule"
                >
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <!-- Editor Tab -->
        <div x-show="activeTab === 'editor'">
            <div 
                x-ref="editor" 
                data-placeholder="{{ $placeholder }}"
                class="prose prose-zinc dark:prose-invert max-w-none focus:outline-none min-h-[300px] p-4 border-l border-r border-b border-zinc-200 dark:border-zinc-700 rounded-b-md bg-white dark:bg-zinc-800"
            ></div>
        </div>

        <!-- HTML Tab -->
        <div x-show="activeTab === 'html'" class="border-l border-r border-b border-zinc-200 dark:border-zinc-700 rounded-b-md">
            <textarea
                x-model="sourceCode"
                class="w-full min-h-[300px] p-4 font-mono text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border-0 rounded-b-md focus:outline-none resize-none"
                placeholder="HTML source code will appear here..."
            ></textarea>
        </div>
        
        <!-- Hidden input to submit with form -->
        <input 
            type="hidden" 
            name="{{ $inputName }}" 
            id="{{ $inputId }}"
            x-model="content"
            @if($required) required @endif
        />
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@once
    @push('styles')
        <style>
            /* TipTap Editor Styles */
            .tiptap-editor-wrapper .ProseMirror {
                outline: none;
            }

            .tiptap-editor-wrapper .ProseMirror p {
                margin: 0.75em 0;
            }

            .tiptap-editor-wrapper .ProseMirror p.is-editor-empty:first-child::before {
                content: attr(data-placeholder);
                float: left;
                color: #9ca3af;
                pointer-events: none;
                height: 0;
            }

            .dark .tiptap-editor-wrapper .ProseMirror p.is-editor-empty:first-child::before {
                color: #71717a;
            }

            .tiptap-editor-wrapper .ProseMirror h1 {
                font-size: 2em;
                font-weight: bold;
                margin: 0.5em 0;
            }

            .tiptap-editor-wrapper .ProseMirror h2 {
                font-size: 1.5em;
                font-weight: bold;
                margin: 0.5em 0;
            }

            .tiptap-editor-wrapper .ProseMirror h3 {
                font-size: 1.25em;
                font-weight: bold;
                margin: 0.5em 0;
            }

            .tiptap-editor-wrapper .ProseMirror ul,
            .tiptap-editor-wrapper .ProseMirror ol {
                padding-left: 1.5em;
                margin: 0.75em 0;
            }

            .tiptap-editor-wrapper .ProseMirror li {
                margin: 0.25em 0;
            }

            .tiptap-editor-wrapper .ProseMirror code {
                background-color: rgba(97, 97, 97, 0.1);
                color: #616161;
                padding: 0.2em 0.4em;
                border-radius: 3px;
                font-family: 'JetBrains Mono', monospace;
                font-size: 0.9em;
            }

            .dark .tiptap-editor-wrapper .ProseMirror code {
                background-color: rgba(255, 255, 255, 0.1);
                color: #e4e4e7;
            }

            .tiptap-editor-wrapper .ProseMirror pre {
                background-color: #f4f4f5;
                color: #18181b;
                padding: 1em;
                border-radius: 5px;
                overflow-x: auto;
                margin: 0.75em 0;
            }

            .dark .tiptap-editor-wrapper .ProseMirror pre {
                background-color: #27272a;
                color: #e4e4e7;
            }

            .tiptap-editor-wrapper .ProseMirror pre code {
                background-color: transparent;
                color: inherit;
                padding: 0;
            }

            .tiptap-editor-wrapper .ProseMirror blockquote {
                border-left: 3px solid #d4d4d8;
                padding-left: 1em;
                margin: 0.75em 0;
                font-style: italic;
            }

            .dark .tiptap-editor-wrapper .ProseMirror blockquote {
                border-left-color: #52525b;
            }

            .tiptap-editor-wrapper .ProseMirror strong {
                font-weight: 600;
            }

            .tiptap-editor-wrapper .ProseMirror em {
                font-style: italic;
            }

            .tiptap-editor-wrapper .ProseMirror a {
                color: #3b82f6;
                text-decoration: underline;
            }

            .dark .tiptap-editor-wrapper .ProseMirror a {
                color: #60a5fa;
            }

            .tiptap-editor-wrapper .ProseMirror:focus {
                outline: none;
            }

            /* HTML Editor Styles */
            .tiptap-editor-wrapper textarea {
                font-family: 'JetBrains Mono', 'Courier New', monospace;
                line-height: 1.6;
                letter-spacing: 0.01em;
            }

            .tiptap-editor-wrapper textarea:focus {
                outline: none;
            }
        </style>
    @endpush
@endonce
