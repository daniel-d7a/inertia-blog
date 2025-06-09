<script setup lang="ts">

import { ChevronDown, ChevronUp } from 'lucide-vue-next';
import { Link, router, usePage } from '@inertiajs/vue3';
import { cn } from '@/lib/utils';
import { VoteEnum, VoteType } from '@/types/AppTypes';

interface Props {
    linkVoteType: NonNullable<VoteType>;
    currentVoteType: VoteType;
    voteHref: string;
    clickHandler?: () => void;
}

const pageUrl = usePage().url

const {
    linkVoteType,
    currentVoteType,
    voteHref
} = defineProps<Props>()

const linkData = {
    vote_type: linkVoteType,
    redirect_to: pageUrl
}

//@finish="refresh"

</script>

<template>
    <Link preserve-state preserve-scroll @click="clickHandler" method="post" :data="linkData"
        :class="cn('hover:bg-gray-200', { 'bg-gray-300': currentVoteType === linkVoteType })" :href="voteHref">

    <template v-if="linkVoteType === VoteEnum.UP">
        <ChevronUp />
    </template>
    <template v-else>
        <ChevronDown />
    </template>
    </Link>
</template>