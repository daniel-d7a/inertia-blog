<script setup lang="ts">
import CharacterCount from '@tiptap/extension-character-count';
import Highlight from '@tiptap/extension-highlight';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import Underline from '@tiptap/extension-underline';
import StarterKit from '@tiptap/starter-kit';
import { BubbleMenu, EditorContent, useEditor } from '@tiptap/vue-3';
import { Image as ImageIcon, Link as LinkIcon, Redo2, Undo2 } from 'lucide-vue-next';
import { watch } from 'vue';
import LinkPopover from './LinkPopover.vue';

interface Props {
    modelValue?: string;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    placeholder: 'What is on your mind ?...',
});

const emit = defineEmits(['update:modelValue', 'change']);

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            codeBlock: {
                HTMLAttributes: {
                    class: 'bg-gray-100 p-4 rounded-md font-mono',
                },
            },
            heading: {
                levels: [1, 2, 3],
            },
        }),
        Image.configure({
            inline: true,
        }),
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'text-blue-500 hover:text-blue-700 underline',
            },
        }),
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        Highlight.configure({
            multicolor: true,
        }),
        Underline,
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
        CharacterCount.configure({
            limit: 10000,
        }),
    ],
    onUpdate: () => {
        const html = editor.value?.getHTML() || '';
        console.log({ html });

        emit('update:modelValue', html);
        emit('change', html);
    },
});

watch(
    () => props.modelValue,
    (value) => {
        const isSame = editor.value?.getHTML() === value;
        if (isSame) return;
        editor.value?.commands.setContent(value, false);
    },
);

const isActive = (name: string, attributes?: Record<string, any>) => {
    return editor.value?.isActive(name, attributes) ?? false;
};

const toggleHeading = (level: 1 | 2 | 3) => {
    editor.value?.chain().focus().toggleHeading({ level }).run();
};

const toggleTextAlign = (align: string) => {
    editor.value?.chain().focus().setTextAlign(align).run();
};

const toggleHighlight = (color: string = '#ffcc00') => {
    editor.value?.chain().focus().toggleHighlight({ color }).run();
};

function setUrlLink(linkUrl: string) {
    editor.value?.chain().focus().extendMarkRange('link').setLink({ href: linkUrl }).run();
}
function removeUrlLink() {
    editor.value?.chain().focus().extendMarkRange('link').unsetLink().run();
}

function setImageLink(linkUrl: string) {
    editor.value?.chain().focus().setImage({ src: linkUrl }).run();
}

// const setLink = () => {
//     const previousUrl = editor.value?.getAttributes('link').href;
//     const url = window.prompt('URL', previousUrl);

//     if (url === null) {
//         return;
//     }

//     if (url === '') {
//         editor.value?.chain().focus().extendMarkRange('link').unsetLink().run();
//         return;
//     }

//     editor.value?.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
// };
</script>

<template>
    <div class="editor-container overflow-hidden rounded-lg border border-gray-300">
        <div v-if="editor" class="editor-toolbar flex flex-wrap items-center gap-1 border-b border-gray-300 bg-gray-50 p-2">
            <!-- Text style buttons -->
            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                :class="{ 'bg-gray-200': isActive('bold') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Bold"
            >
                <span class="font-bold">B</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                :class="{ 'bg-gray-200': isActive('italic') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Italic"
            >
                <span class="italic">I</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleUnderline().run()"
                :class="{ 'bg-gray-200': isActive('underline') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Underline"
            >
                <span class="underline">U</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleStrike().run()"
                :class="{ 'bg-gray-200': isActive('strike') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Strikethrough"
            >
                <span class="line-through">S</span>
            </button>
            <button
                type="button"
                @click="toggleHighlight()"
                :class="{ 'bg-gray-200': isActive('highlight') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Highlight"
            >
                <span class="bg-yellow-300 px-1">H</span>
            </button>

            <div class="mx-2 h-6 border-l border-gray-300"></div>

            <!-- Headings -->
            <button
                type="button"
                @click="toggleHeading(1)"
                :class="{ 'bg-gray-200': isActive('heading', { level: 1 }) }"
                class="rounded p-2 hover:bg-gray-100"
                title="Heading 1"
            >
                <span class="text-lg font-bold">H1</span>
            </button>
            <button
                type="button"
                @click="toggleHeading(2)"
                :class="{ 'bg-gray-200': isActive('heading', { level: 2 }) }"
                class="rounded p-2 hover:bg-gray-100"
                title="Heading 2"
            >
                <span class="font-bold">H2</span>
            </button>
            <button
                type="button"
                @click="toggleHeading(3)"
                :class="{ 'bg-gray-200': isActive('heading', { level: 3 }) }"
                class="rounded p-2 hover:bg-gray-100"
                title="Heading 3"
            >
                <span class="text-sm font-bold">H3</span>
            </button>

            <div class="mx-2 h-6 border-l border-gray-300"></div>

            <!-- Lists -->
            <button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'bg-gray-200': isActive('bulletList') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Bullet List"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'bg-gray-200': isActive('orderedList') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Ordered List"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="10" y1="6" x2="21" y2="6"></line>
                    <line x1="10" y1="12" x2="21" y2="12"></line>
                    <line x1="10" y1="18" x2="21" y2="18"></line>
                    <path d="M4 6h1v4"></path>
                    <path d="M4 10h2"></path>
                    <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleCodeBlock().run()"
                :class="{ 'bg-gray-200': isActive('codeBlock') }"
                class="rounded p-2 hover:bg-gray-100"
                title="Code Block"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <polyline points="16 18 22 12 16 6"></polyline>
                    <polyline points="8 6 2 12 8 18"></polyline>
                </svg>
            </button>

            <div class="mx-2 h-6 border-l border-gray-300"></div>

            <!-- Alignment -->
            <button
                type="button"
                @click="toggleTextAlign('left')"
                :class="{ 'bg-gray-200': isActive('', { textAlign: 'left' }) }"
                class="rounded p-2 hover:bg-gray-100"
                title="Align Left"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="17" y1="10" x2="3" y2="10"></line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="3" y2="14"></line>
                    <line x1="17" y1="18" x2="3" y2="18"></line>
                </svg>
            </button>
            <button
                type="button"
                @click="toggleTextAlign('center')"
                :class="{ 'bg-gray-200': isActive('', { textAlign: 'center' }) }"
                class="rounded p-2 hover:bg-gray-100"
                title="Align Center"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="18" y1="10" x2="6" y2="10"></line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="3" y2="14"></line>
                    <line x1="18" y1="18" x2="6" y2="18"></line>
                </svg>
            </button>
            <button
                type="button"
                @click="toggleTextAlign('right')"
                :class="{ 'bg-gray-200': isActive('', { textAlign: 'right' }) }"
                class="rounded p-2 hover:bg-gray-100"
                title="Align Right"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="21" y1="10" x2="7" y2="10"></line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="9" y2="14"></line>
                    <line x1="21" y1="18" x2="7" y2="18"></line>
                </svg>
            </button>

            <div class="mx-2 h-6 border-l border-gray-300"></div>

            <!-- Other elements -->
            <LinkPopover :set-link="setUrlLink" :remove-link="removeUrlLink">
                <LinkIcon :size="16" />
            </LinkPopover>
            <LinkPopover :set-link="setImageLink">
                <ImageIcon :size="16" />
            </LinkPopover>

            <button
                type="button"
                @click="editor.chain().focus().setHorizontalRule().run()"
                class="rounded p-2 hover:bg-gray-100"
                title="Horizontal Rule"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>

            <div class="mx-2 h-6 border-l border-gray-300"></div>

            <!-- History -->
            <button
                type="button"
                @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().undo()"
                class="rounded p-2 hover:bg-gray-100 disabled:opacity-50"
                title="Undo"
            >
                <Undo2 :size="18" />
            </button>
            <button
                type="button"
                @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().redo()"
                class="rounded p-2 hover:bg-gray-100 disabled:opacity-50"
                title="Redo"
            >
                <Redo2 :size="18" />
            </button>
        </div>

        <BubbleMenu :editor="editor" v-if="editor" class="flex gap-1 rounded-md border border-gray-200 bg-white p-1 shadow-lg">
            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                :class="{ 'bg-gray-200': editor.isActive('bold') }"
                class="rounded p-1 hover:bg-gray-100"
            >
                <span class="text-sm font-bold">B</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                :class="{ 'bg-gray-200': editor.isActive('italic') }"
                class="rounded p-1 hover:bg-gray-100"
            >
                <span class="text-sm italic">I</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleUnderline().run()"
                :class="{ 'bg-gray-200': editor.isActive('underline') }"
                class="rounded p-1 hover:bg-gray-100"
            >
                <span class="text-sm underline">U</span>
            </button>
        </BubbleMenu>

        <EditorContent :editor="editor" class="editor-content min-h-[200px] p-4 focus:outline-none" />
    </div>
</template>

<style>
/* Basic editor styles */
.ProseMirror {
    min-height: 200px;
    outline: none;
}

.ProseMirror:focus {
    outline: none;
}

.ProseMirror p {
    margin-bottom: 1rem;
}

.ProseMirror h1 {
    font-size: 2em;
    margin: 0.67em 0;
    font-weight: bold;
}

.ProseMirror h2 {
    font-size: 1.5em;
    margin: 0.75em 0;
    font-weight: bold;
}

.ProseMirror h3 {
    font-size: 1.17em;
    margin: 0.83em 0;
    font-weight: bold;
}

.ProseMirror ul,
.ProseMirror ol {
    padding: 0 1rem;
    margin-bottom: 1rem;
}

.ProseMirror ul {
    list-style-type: disc;
}

.ProseMirror ol {
    list-style-type: decimal;
}

.ProseMirror blockquote {
    padding-left: 1rem;
    border-left: 2px solid #ddd;
    margin-bottom: 1rem;
}

.ProseMirror img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
}

.ProseMirror img.ProseMirror-selectednode {
    outline: 2px solid #68cef8;
}

.ProseMirror hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 2rem 0;
}

.tiptap p.is-editor-empty:first-child::before {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
