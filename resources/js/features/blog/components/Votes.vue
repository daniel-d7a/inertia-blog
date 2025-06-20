<script setup lang="ts">
import { VotableType, VoteEnum, type VoteType } from '@/types/AppTypes';
import { ref } from 'vue';
import VoteLink from './VoteLink.vue';

interface Props {
    votes: number | null;
    currentVote: VoteType;
    voteableType: VotableType;
    voteable_id: number;
}

const { currentVote, votes, voteableType, voteable_id } = defineProps<Props>();

const optimisticVotes = ref(votes ?? 0);
const optimisticCurrentVote = ref(currentVote);

const linkData = {
    voteable_type: voteableType,
    voteable_id: voteable_id,
};

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
            :link-data="linkData"
        />
        {{ optimisticVotes }}
        <VoteLink
            :click-handler="() => voteOptimistic(VoteEnum.DOWN)"
            :current-vote-type="optimisticCurrentVote"
            :link-vote-type="VoteEnum.DOWN"
            :link-data="linkData"
        />
    </div>
</template>
