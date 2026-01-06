# TipTap PHP/Laravel Integration Guide

Bu dokümantasyon TipTap editor'ün PHP ve Laravel ile kullanımı için hazırlanmıştır.

**Kaynak:** [tiptap.dev](https://tiptap.dev/docs/editor/getting-started/install/php)

---

## İçindekiler

1. [Kurulum](#kurulum)
2. [Laravel Livewire Entegrasyonu](#laravel-livewire-entegrasyonu)
3. [Alpine.js ile Kullanım](#alpinejs-ile-kullanım)
4. [Yapılandırma](#yapılandırma)
5. [Stil Verme](#stil-verme)
6. [İçerik Kaydetme](#içerik-kaydetme)

---

## Kurulum

### NPM Paketlerini Kurun

```bash
npm install @tiptap/core @tiptap/starter-kit
```

### CDN ile Kullanım (Alternatif)

```html
<script src="https://cdn.jsdelivr.net/npm/@tiptap/core@latest/dist/index.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tiptap/starter-kit@latest/dist/index.umd.js"></script>
```

---

## Laravel Livewire Entegrasyonu

### 1. Blade Component Oluşturma

`resources/views/components/editor.blade.php` dosyasını oluşturun:

```blade
<div
  x-data="setupEditor(
    $wire.entangle('{{ $attributes->wire('model')->value() }}').defer
  )"
  x-init="() => init($refs.editor)"
  wire:ignore
  {{ $attributes->whereDoesntStartWith('wire:model') }}
>
  <div x-ref="editor"></div>
</div>
```

### 2. JavaScript Setup

`resources/js/tiptap.js` dosyasını oluşturun:

```javascript
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";

window.setupEditor = function (content) {
    let editor;

    return {
        content: content,

        init(element) {
            editor = new Editor({
                element: element,
                extensions: [StarterKit],
                content: this.content,
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                },
            });

            this.$watch("content", (content) => {
                // If the new content matches Tiptap's then we just skip.
                if (content === editor.getHTML()) return;

                /*
          Otherwise, it means that an external source
          is modifying the data on this Alpine component,
          which could be Livewire itself.
          In this case, we only need to update Tiptap's
          content and we're done.
        */
                editor.commands.setContent(content, false);
            });
        },
    };
};
```

### 3. Vite Config'e Ekleme

`vite.config.js` dosyasına ekleyin:

```javascript
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/tiptap.js", // TipTap ekleyin
            ],
            refresh: true,
        }),
    ],
});
```

### 4. Layout'a Script Ekleme

`resources/views/layouts/app.blade.php` veya layout dosyanıza:

```blade
@vite(['resources/js/tiptap.js'])
```

### 5. Livewire Component'te Kullanım

```blade
<!-- my-livewire-component.blade.php -->
<x-editor wire:model="content" wire:poll.10000ms="autosave"></x-editor>
```

**Not:** Livewire v3'te `.defer` modifier artık yok, state güncellemeleri varsayılan olarak defer edilir. Server-side güncelleme için `.live` modifier kullanın.

---

## Alpine.js ile Kullanım

### Basit Örnek

```html
<div
    x-data="{ editor: null, content: '<p>Hello World!</p>' }"
    x-init="
  editor = new Editor({
    element: $refs.editor,
    extensions: [StarterKit],
    content: content,
    onUpdate: ({ editor }) => {
      content = editor.getHTML()
    }
  })
"
>
    <div x-ref="editor"></div>
</div>
```

---

## Yapılandırma

### Extensions Ekleme

```javascript
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import Link from "@tiptap/extension-link";
import Image from "@tiptap/extension-image";

const editor = new Editor({
    extensions: [
        StarterKit,
        Link.configure({
            openOnClick: false,
        }),
        Image,
    ],
    content: "<p>Hello World!</p>",
});
```

### Editor Ayarları

```javascript
const editor = new Editor({
    extensions: [StarterKit],
    content: "<p>Hello World!</p>",
    editable: true,
    autofocus: true,
    injectCSS: true,
});
```

---

## Stil Verme

### CSS ile Stil Verme

```css
/* TipTap Editor Container */
.ProseMirror {
    outline: none;
    padding: 1rem;
    min-height: 200px;
}

.ProseMirror p {
    margin: 1em 0;
}

.ProseMirror h1 {
    font-size: 2em;
    font-weight: bold;
    margin: 0.5em 0;
}

.ProseMirror h2 {
    font-size: 1.5em;
    font-weight: bold;
    margin: 0.5em 0;
}

.ProseMirror ul,
.ProseMirror ol {
    padding-left: 2em;
}

.ProseMirror code {
    background-color: #f4f4f4;
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: monospace;
}

.ProseMirror pre {
    background-color: #f4f4f4;
    padding: 1em;
    border-radius: 5px;
    overflow-x: auto;
}

.ProseMirror pre code {
    background-color: transparent;
    padding: 0;
}
```

### Tailwind CSS ile Stil Verme

```html
<div class="prose prose-lg max-w-none">
    <div x-ref="editor"></div>
</div>
```

---

## İçerik Kaydetme

### HTML Olarak Kaydetme

```javascript
const html = editor.getHTML();
```

### JSON Olarak Kaydetme

```javascript
const json = editor.getJSON();
```

### Text Olarak Kaydetme

```javascript
const text = editor.getText();
```

### Laravel Controller'da Kaydetme

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'content' => 'required|string',
    ]);

    // HTML içeriği kaydet
    $post = Post::create([
        'content' => $validated['content'],
    ]);

    return redirect()->back()->with('success', 'Post saved!');
}
```

---

## Örnek: Blog Post Editor

### Livewire Component

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostEditor extends Component
{
    public $content = '';

    public function save()
    {
        Post::create([
            'content' => $this->content,
        ]);

        session()->flash('message', 'Post saved!');
    }

    public function render()
    {
        return view('livewire.post-editor');
    }
}
```

### Blade View

```blade
<div>
    <x-editor wire:model="content" />

    <button wire:click="save" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
        Save Post
    </button>

    @if (session()->has('message'))
        <div class="mt-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif
</div>
```

---

## Yaygın Extensions

### Link Extension

```bash
npm install @tiptap/extension-link
```

```javascript
import Link from "@tiptap/extension-link";

const editor = new Editor({
    extensions: [
        StarterKit,
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: "text-blue-500 hover:underline",
            },
        }),
    ],
});
```

### Image Extension

```bash
npm install @tiptap/extension-image
```

```javascript
import Image from "@tiptap/extension-image";

const editor = new Editor({
    extensions: [
        StarterKit,
        Image.configure({
            inline: true,
            allowBase64: true,
        }),
    ],
});
```

### Placeholder Extension

```bash
npm install @tiptap/extension-placeholder
```

```javascript
import Placeholder from "@tiptap/extension-placeholder";

const editor = new Editor({
    extensions: [
        StarterKit,
        Placeholder.configure({
            placeholder: "Start typing...",
        }),
    ],
});
```

---

## Sorun Giderme

### Livewire ile Çalışmıyor

-   `wire:ignore` attribute'unu editor container'a ekleyin
-   `.defer` modifier kullanın (Livewire v2) veya `.live` (Livewire v3)
-   `$wire.entangle()` kullanarak two-way binding sağlayın

### İçerik Güncellenmiyor

-   `editor.commands.setContent()` kullanarak içeriği güncelleyin
-   `onUpdate` callback'inde `editor.getHTML()` kullanın

### Stiller Uygulanmıyor

-   `injectCSS: false` yapıp kendi CSS'inizi yazın
-   ProseMirror class'larını kullanarak stil verin

---

## Kaynaklar

-   [TipTap Resmi Dokümantasyon](https://tiptap.dev/)
-   [TipTap PHP Dokümantasyonu](https://tiptap.dev/docs/editor/getting-started/install/php)
-   [TipTap Extensions](https://tiptap.dev/docs/editor/extensions)
-   [Laravel Livewire](https://laravel-livewire.com/)
-   [Alpine.js](https://alpinejs.dev/)

---

## Notlar

-   TipTap ücretsiz ve açık kaynaklıdır
-   Pro versiyonu daha fazla özellik sunar
-   Laravel ve Livewire ile mükemmel uyumludur
-   Alpine.js ile kolayca entegre edilebilir

---

**Son Güncelleme:** 2025-01-27
**Versiyon:** TipTap 3.x
