<script setup lang="ts">
import { VoteEnum, type VoteType } from '@/types/AppTypes';
import { ref } from 'vue';
import VoteLink from './VoteLink.vue';

interface Props {
    votes: number;
    currentVote: VoteType;
    voteHref: string;
}

const { currentVote, votes, voteHref } = defineProps<Props>();

const optimisticVotes = ref(votes);
const optimisticCurrentVote = ref(currentVote);

function voteOptimistic(newVote: VoteType) {
    // if no vote and I vote add vote
    if (!optimisticCurrentVote.value) {
        optimisticVotes.value += Number(newVote);
        optimisticCurrentVote.value = newVote;
    }
    // if vote and I vote different add double the vote
    else if (optimisticCurrentVote.value !== newVote) {
        optimisticVotes.value += Number(newVote) * 2;
        optimisticCurrentVote.value = newVote;
    }
    // if vote and I vote same remove vote
    else {
        optimisticVotes.value -= Number(newVote);
        optimisticCurrentVote.value = VoteEnum.NONE;
    }
}
</script>

<template>
    <div class="mt-2 mr-4 flex flex-col items-center justify-between gap-2 text-xl">
        <VoteLink
            :click-handler="() => voteOptimistic(VoteEnum.UP)"
            :current-vote-type="optimisticCurrentVote"
            :link-vote-type="VoteEnum.UP"
            :vote-href="voteHref"
        />
        {{ optimisticVotes }}
        <VoteLink
            :click-handler="() => voteOptimistic(VoteEnum.DOWN)"
            :current-vote-type="optimisticCurrentVote"
            :link-vote-type="VoteEnum.DOWN"
            :vote-href="voteHref"
        />
    </div>
</template>
