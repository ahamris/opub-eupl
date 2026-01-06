import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import Placeholder from "@tiptap/extension-placeholder";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";
import TaskList from "@tiptap/extension-task-list";
import TaskItem from "@tiptap/extension-task-item";
import Underline from "@tiptap/extension-underline";
import Subscript from "@tiptap/extension-subscript";
import Superscript from "@tiptap/extension-superscript";

window.setupEditor = function (content, placeholder = "Start typing...") {
    let editor;

    return {
        content: content,
        updatedAt: Date.now(),

        init(element) {
            const placeholderText = element.getAttribute("data-placeholder") || placeholder;
            
            editor = new Editor({
                element: element,
                extensions: [
                    StarterKit,
                    Placeholder.configure({
                        placeholder: placeholderText,
                    }),
                    Link.configure({
                        openOnClick: false,
                        HTMLAttributes: {
                            class: 'text-blue-600 dark:text-blue-400 hover:underline',
                        },
                    }),
                    TextAlign.configure({
                        types: ['heading', 'paragraph'],
                    }),
                    TaskList,
                    TaskItem.configure({
                        nested: true,
                    }),
                    Underline,
                    Subscript,
                    Superscript,
                ],
                content: this.content,
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                    this.updatedAt = Date.now();
                },
                onSelectionUpdate: () => {
                    this.updatedAt = Date.now();
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

        isLoaded() {
            return !!editor;
        },

        isActive(type, opts = {}) {
            if (!editor) return false;
            return editor.isActive(type, opts);
        },

        toggleHeading(level) {
            if (!editor) return;
            editor.chain().focus().toggleHeading({ level }).run();
        },

        toggleBold() {
            if (!editor) return;
            editor.chain().focus().toggleBold().run();
        },

        toggleItalic() {
            if (!editor) return;
            editor.chain().focus().toggleItalic().run();
        },

        toggleStrike() {
            if (!editor) return;
            editor.chain().focus().toggleStrike().run();
        },

        toggleCode() {
            if (!editor) return;
            editor.chain().focus().toggleCode().run();
        },

        toggleBulletList() {
            if (!editor) return;
            editor.chain().focus().toggleBulletList().run();
        },

        toggleOrderedList() {
            if (!editor) return;
            editor.chain().focus().toggleOrderedList().run();
        },

        toggleBlockquote() {
            if (!editor) return;
            editor.chain().focus().toggleBlockquote().run();
        },

        setParagraph() {
            if (!editor) return;
            editor.chain().focus().setParagraph().run();
        },

        setCodeBlock() {
            if (!editor) return;
            editor.chain().focus().toggleCodeBlock().run();
        },

        setHorizontalRule() {
            if (!editor) return;
            editor.chain().focus().setHorizontalRule().run();
        },

        getHTML() {
            if (!editor) return '';
            return editor.getHTML();
        },

        setHTML(html) {
            if (!editor) return;
            editor.commands.setContent(html, false);
            this.content = html;
        },

        formatHTML(html) {
            if (!html || html.trim() === '') return '';
            
            // Simple HTML formatter
            let formatted = html;
            let indent = 0;
            const indentSize = 2;
            
            // Add line breaks before opening tags
            formatted = formatted.replace(/>/g, '>\n');
            formatted = formatted.replace(/</g, '\n<');
            
            // Split into lines and process
            const lines = formatted.split('\n');
            const formattedLines = [];
            
            for (let line of lines) {
                line = line.trim();
                if (!line) continue;
                
                // Decrease indent for closing tags
                if (line.match(/^<\/\w/)) {
                    indent = Math.max(0, indent - indentSize);
                }
                
                // Add indented line
                formattedLines.push(' '.repeat(indent) + line);
                
                // Increase indent for opening tags (but not self-closing)
                if (line.match(/^<\w[^>]*>/) && !line.match(/\/>$/)) {
                    indent += indentSize;
                }
            }
            
            return formattedLines.join('\n').trim();
        },

        undo() {
            if (!editor) return;
            editor.chain().focus().undo().run();
        },

        redo() {
            if (!editor) return;
            editor.chain().focus().redo().run();
        },

        toggleUnderline() {
            if (!editor) return;
            editor.chain().focus().toggleUnderline().run();
        },

        toggleSubscript() {
            if (!editor) return;
            editor.chain().focus().toggleSubscript().run();
        },

        toggleSuperscript() {
            if (!editor) return;
            editor.chain().focus().toggleSuperscript().run();
        },

        toggleTaskList() {
            if (!editor) return;
            editor.chain().focus().toggleTaskList().run();
        },

        setLink() {
            if (!editor) return;
            const url = window.prompt('Enter URL:');
            if (url) {
                editor.chain().focus().setLink({ href: url }).run();
            }
        },

        unsetLink() {
            if (!editor) return;
            editor.chain().focus().unsetLink().run();
        },

        setTextAlign(align) {
            if (!editor) return;
            editor.chain().focus().setTextAlign(align).run();
        },
    };
};
