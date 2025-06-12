import { SharedData, User } from '.';

export type CustomProps<T = unknown> = {
    responseData: T;
    sharedData: SharedData;
};

export interface Paginated<TData> {
    data: TData[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: number | '&laquo; Previous' | 'Next &raquo;';
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

interface Entity {
    id: number;
}

interface WithTimestamps {
    created_at: Date;
    updated_at: Date;
}

interface WithVotes {
    votes: number | null;
    current_user_vote?: VoteType;
}

export const VoteEnum = {
    UP: '1',
    DOWN: '-1',
    NONE: undefined,
} as const;

export type VoteType = (typeof VoteEnum)[keyof typeof VoteEnum];

export interface Tag extends Entity, WithTimestamps {
    name: string;
}

export interface Post extends Entity, WithTimestamps, WithVotes {
    title: string;
    body: string;
    user: User;
    tags: Tag[];
    slug: string;
}

export interface Comment extends Entity, WithTimestamps, WithVotes {
    body: string;
    user: User;
}

export const VotableTypesEnum = {
    POST: 'Post',
    COMMENT: 'Comment',
} as const;

export type VotableType = (typeof VotableTypesEnum)[keyof typeof VotableTypesEnum];

export interface Vote {
    votable_id: number;
    votable_type: VotableType;
    vote_type: NonNullable<VoteType>;
    user_id: number;
}
