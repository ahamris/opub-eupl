@props([
    'value' => '',
    'placeholder' => '',
    'theme' => 'snow', // snow, bubble
    'height' => '200px',
    'formats' => null, // array of format names to enable, null = all formats enabled
    'toolbar' => null, // custom toolbar configuration, null = default toolbar
    'name' => null,
])

@php
    $editorId = 'quill-' . uniqid();
    $hasWireModel = $attributes->whereStartsWith('wire:model')->isNotEmpty();
    $wireModelValue = $hasWireModel ? $attributes->wire('model')->value() : null;
@endphp

<div 
    @if($hasWireModel)
        x-data="{
            content: $wire.{{ $wireModelValue }}.live,
            editor: null,
            init() {
                if (!window.Quill) {
                    setTimeout(() => this.init(), 100);
                    return;
                }
                
                const options = {
                    theme: @js($theme),
                    placeholder: @js($placeholder),
                };
                
                @if($formats !== null)
                options.formats = @js($formats);
                @endif
                
                @if($toolbar !== null)
                options.modules = {
                    toolbar: @js($toolbar)
                };
                @endif
                
                this.editor = new window.Quill(this.$refs.editor, options);
                
                if (this.content) {
                    this.editor.root.innerHTML = this.content;
                }
                
                this.editor.on('text-change', () => {
                    this.content = this.editor.root.innerHTML;
                });
                
                this.$watch('content', (newContent) => {
                    if (!this.editor) return;
                    if (newContent === this.editor.root.innerHTML) return;
                    this.editor.root.innerHTML = newContent || '';
                });
            },
            destroy() {
                if (this.editor) {
                    this.editor = null;
                }
            }
        }"
    @else
        x-data="{
            content: @js($value),
            editor: null,
            init() {
                if (!window.Quill) {
                    setTimeout(() => this.init(), 100);
                    return;
                }
                
                const options = {
                    theme: @js($theme),
                    placeholder: @js($placeholder),
                };
                
                @if($formats !== null)
                options.formats = @js($formats);
                @endif
                
                @if($toolbar !== null)
                options.modules = {
                    toolbar: @js($toolbar)
                };
                @endif
                
                this.editor = new window.Quill(this.$refs.editor, options);
                
                if (this.content) {
                    this.editor.root.innerHTML = this.content;
                }
                
                this.editor.on('text-change', () => {
                    this.content = this.editor.root.innerHTML;
                });
                
                this.$watch('content', (newContent) => {
                    if (!this.editor) return;
                    if (newContent === this.editor.root.innerHTML) return;
                    this.editor.root.innerHTML = newContent || '';
                });
            },
            destroy() {
                if (this.editor) {
                    this.editor = null;
                }
            }
        }"
    @endif
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'quill-wrapper']) }}
    style="min-height: {{ $height }};"
>
    <div 
        x-ref="editor"
        id="{{ $editorId }}"
        class="bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-b-md"
    ></div>
    
    @if($name)
        <input type="hidden" name="{{ $name }}" x-model="content">
    @endif
</div>

@once
    @push('styles')
        <style>
            .quill-wrapper .ql-container {
                @apply border-0 rounded-b-md;
                min-height: inherit;
                background-color: white;
            }
            
            .dark .quill-wrapper .ql-container {
                background-color: var(--color-zinc-800);
            }
            
            .quill-wrapper .ql-toolbar {
                @apply border-b rounded-t-md;
                border-color: var(--color-zinc-300);
                background-color: var(--color-zinc-50);
            }
            
            .dark .quill-wrapper .ql-toolbar {
                border-color: var(--color-zinc-700);
                background-color: var(--color-zinc-900);
            }
            
            .quill-wrapper .ql-editor {
                min-height: inherit;
                color: var(--color-zinc-900);
            }
            
            .dark .quill-wrapper .ql-editor {
                color: var(--color-zinc-100);
            }
            
            .quill-wrapper .ql-editor.ql-blank::before {
                color: var(--color-zinc-400);
            }
            
            .dark .quill-wrapper .ql-editor.ql-blank::before {
                color: var(--color-zinc-500);
            }
            
            /* Snow theme - Toolbar icons */
            .quill-wrapper .ql-snow .ql-stroke {
                stroke: var(--color-zinc-700);
            }
            
            .dark .quill-wrapper .ql-snow .ql-stroke {
                stroke: var(--color-zinc-300);
            }
            
            .quill-wrapper .ql-snow .ql-fill {
                fill: var(--color-zinc-700);
            }
            
            .dark .quill-wrapper .ql-snow .ql-fill {
                fill: var(--color-zinc-300);
            }
            
            .quill-wrapper .ql-snow .ql-picker-label {
                color: var(--color-zinc-700);
            }
            
            .dark .quill-wrapper .ql-snow .ql-picker-label {
                color: var(--color-zinc-300);
            }
            
            .quill-wrapper .ql-snow .ql-picker-options {
                background-color: var(--color-zinc-50);
                border-color: var(--color-zinc-300);
            }
            
            .dark .quill-wrapper .ql-snow .ql-picker-options {
                background-color: var(--color-zinc-800);
                border-color: var(--color-zinc-700);
            }
            
            .quill-wrapper .ql-snow .ql-picker-item {
                color: var(--color-zinc-900);
            }
            
            .dark .quill-wrapper .ql-snow .ql-picker-item {
                color: var(--color-zinc-100);
            }
            
            .quill-wrapper .ql-snow .ql-picker-item:hover {
                background-color: var(--color-zinc-100);
                color: var(--color-accent);
            }
            
            .dark .quill-wrapper .ql-snow .ql-picker-item:hover {
                background-color: var(--color-zinc-700);
                color: var(--color-accent);
            }
            
            .quill-wrapper .ql-snow .ql-picker-item.ql-selected {
                color: var(--color-accent);
            }
            
            .quill-wrapper .ql-snow button:hover,
            .quill-wrapper .ql-snow button.ql-active {
                color: var(--color-accent);
            }
            
            .quill-wrapper .ql-snow button:hover .ql-stroke,
            .quill-wrapper .ql-snow button.ql-active .ql-stroke {
                stroke: var(--color-accent);
            }
            
            .quill-wrapper .ql-snow button:hover .ql-fill,
            .quill-wrapper .ql-snow button.ql-active .ql-fill {
                fill: var(--color-accent);
            }
            
            /* Bubble theme - Toolbar */
            .quill-wrapper .ql-bubble .ql-toolbar {
                background-color: var(--color-zinc-900);
                border-color: var(--color-zinc-700);
            }
            
            .dark .quill-wrapper .ql-bubble .ql-toolbar {
                background-color: var(--color-zinc-800);
                border-color: var(--color-zinc-600);
            }
            
            .quill-wrapper .ql-bubble .ql-stroke {
                stroke: var(--color-zinc-300);
            }
            
            .dark .quill-wrapper .ql-bubble .ql-stroke {
                stroke: var(--color-zinc-400);
            }
            
            .quill-wrapper .ql-bubble .ql-fill {
                fill: var(--color-zinc-300);
            }
            
            .dark .quill-wrapper .ql-bubble .ql-fill {
                fill: var(--color-zinc-400);
            }
            
            .quill-wrapper .ql-bubble button:hover,
            .quill-wrapper .ql-bubble button.ql-active {
                color: var(--color-accent);
            }
            
            .quill-wrapper .ql-bubble button:hover .ql-stroke,
            .quill-wrapper .ql-bubble button.ql-active .ql-stroke {
                stroke: var(--color-accent);
            }
            
            .quill-wrapper .ql-bubble button:hover .ql-fill,
            .quill-wrapper .ql-bubble button.ql-active .ql-fill {
                fill: var(--color-accent);
            }
            
            /* Editor border */
            .quill-wrapper > div {
                border-color: var(--color-zinc-300);
            }
            
            .dark .quill-wrapper > div {
                border-color: var(--color-zinc-700);
            }

            /* Fixed height mode */
            .quill-wrapper.fixed-height {
                display: flex;
                flex-direction: column;
            }
            
            .quill-wrapper.fixed-height .ql-container {
                flex: 1;
                overflow-y: auto;
                min-height: 0 !important;
            }
        </style>
    @endpush
@endonce

