<script setup lang="ts">
import { route } from '@/helpers/route';
import { cn } from '@/lib/utils';
import { SharedData } from '@/types';
import { VotableType, VoteEnum, VoteType } from '@/types/AppTypes';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';

interface Props {
    linkVoteType: NonNullable<VoteType>;
    currentVoteType: VoteType;
    linkData: { voteable_type: VotableType; voteable_id: number };
    clickHandler?: () => void;
}

const pageUrl = usePage<SharedData>().url;

const { linkVoteType, currentVoteType, linkData } = defineProps<Props>();
</script>

<!-- TODO:change arrows to something that can be an outline or solid -->

<template>
    <Link
        preserve-state
        preserve-scroll
        @click="clickHandler"
        method="post"
        :data="{ ...linkData, vote_type: linkVoteType, redirect_to: pageUrl }"
        :class="cn('hover:bg-gray-200', { 'bg-gray-300': currentVoteType === linkVoteType })"
        :href="route('vote.update')"
    >
        <template v-if="linkVoteType === VoteEnum.UP">
            <ChevronUp />
        </template>
        <template v-else>
            <ChevronDown />
        </template>
    </Link>
</template>
