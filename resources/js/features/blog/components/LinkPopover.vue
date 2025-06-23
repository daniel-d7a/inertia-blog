<script setup lang="ts">
import { Check, Trash } from 'lucide-vue-next';
import { computed, CSSProperties, onBeforeUnmount, onMounted, ref } from 'vue';

interface Props {
    setLink: (linkUrl: string) => void;
    removeLink?: () => void;
}

const { removeLink, setLink } = defineProps<Props>();

const linkUrl = ref('');
const isActive = ref(false);
const popoverRef = ref<HTMLElement | null>(null);
const triggerRef = ref<HTMLElement | null>(null);
const popoverStyle = computed(() => updatePosition());
const showPopover = ref(false);

// Update popover position
const updatePosition = (): CSSProperties => {
    const startPosition: CSSProperties = {
        position: 'absolute',
        top: `0`,
        left: `0`,
        opacity: '0',
        visibility: 'hidden',
    };

    const btn = triggerRef.value;
    if (!btn) return startPosition;
    const rect = btn.getBoundingClientRect();
    const popover = popoverRef.value;

    if (!popover) return startPosition;

    // Calculate position
    const popoverWidth = popover.offsetWidth;
    const popoverHeight = popover.offsetHeight;
    const windowWidth = window.innerWidth;
    const windowHeight = window.innerHeight;

    let left = rect.left + window.scrollX;
    let top = rect.bottom + window.scrollY + 8;

    // Adjust if near right edge
    if (left + popoverWidth > windowWidth) {
        left = windowWidth - popoverWidth - 10;
    }

    // Adjust if near bottom edge
    if (top + popoverHeight > windowHeight + window.scrollY) {
        top = rect.top + window.scrollY - popoverHeight - 8;
    }

    return {
        position: 'absolute',
        top: `${top}px`,
        left: `${left}px`,
        opacity: '1',
        visibility: showPopover.value ? 'visible' : 'hidden',
    };
};

function linkSetHandler() {
    if (linkUrl.value) {
        setLink(linkUrl.value);
    } else {
        removeLink?.();
    }
    closePopover();
}

function linkRemoveHandler() {
    removeLink?.();
    closePopover();
}

function closePopover() {
    showPopover.value = false;
}

// Handle clicks outside
const handleClickOutside = (event: MouseEvent) => {
    if (popoverRef.value && !popoverRef.value.contains(event.target as Node)) {
        closePopover();
    }
};

// Keyboard navigation
const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        closePopover();
    }
};

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside);
    document.addEventListener('keydown', handleKeyDown);
});

onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleClickOutside);
    document.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
    <button ref="triggerRef" type="button" @click="showPopover = true" class="rounded p-2 hover:bg-gray-100" title="Link">
        <slot />
    </button>
    <div
        ref="popoverRef"
        class="link-popover z-[100] min-w-[300px] rounded-md border border-gray-200 bg-white p-2 shadow-lg transition-all duration-200"
        :style="popoverStyle"
    >
        <div class="flex items-center">
            <input
                v-model="linkUrl"
                type="url"
                placeholder="https://example.com"
                class="w-full border-b border-gray-300 focus:outline-none"
                @keydown.enter="linkSetHandler"
                autofocus
            />
            <div class="mx-2 h-6 border-l border-gray-300"></div>

            <div class="flex gap-2 pt-2">
                <button type="button" @click="linkSetHandler" class="hover:text-app-blue text-sm text-gray-400"><Check :size="20" /></button>
                <button type="button" v-if="isActive && removeLink" @click="linkRemoveHandler" class="text-sm text-gray-400 hover:text-red-600">
                    <Trash :size="20" />
                </button>
            </div>
        </div>

        <!-- Arrow pointing to selection -->
        <div class="absolute -top-2 left-4 h-4 w-4 rotate-45 transform border-t border-l border-gray-200 bg-white"></div>
    </div>
</template>
