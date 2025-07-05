import { Tag } from '@/types/AppTypes';
import { ref } from 'vue';

export type PostTag = Tag | string;

export function useTagDropDown(initialValue?: PostTag[]) {
    initialValue = initialValue ?? [];

    const selectedTags = ref<PostTag[]>(initialValue);

    function onRemoveTag(tag: Tag | string) {
        const index = selectedTags.value.indexOf(tag);
        if (index !== -1) selectedTags.value.splice(index, 1);
    }
    function onSelectTag(tag: Tag | string) {
        if (!selectedTags.value.includes(tag)) selectedTags.value.push(tag);
    }

    function getTagsData() {
        return selectedTags.value.reduce(
            (acc, curr) => {
                if (typeof curr === 'string') {
                    acc.newTags.push(curr);
                } else {
                    acc.existingTags.push(curr);
                }
                return acc;
            },
            { newTags: [] as string[], existingTags: [] as Tag[] },
        );
    }

    return { selectedTags, onRemoveTag, onSelectTag, getTagsData };
}
