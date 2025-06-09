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

export const VoteEnum = {
    UP: '1',
    DOWN: '-1',
    NONE: undefined,
} as const;

export type VoteType = (typeof VoteEnum)[keyof typeof VoteEnum];

export interface Tag extends Entity, WithTimestamps {
    name: string;
}

export interface PostVote extends Entity, WithTimestamps {
    user_id: number;
    post_id: number;
    vote_type: NonNullable<VoteType>;
}

export interface CommentVote extends Entity, WithTimestamps {
    user_id: number;
    comment_id: number;
    vote_type: NonNullable<VoteType>;
}

export interface Post extends Entity, WithTimestamps {
    title: string;
    body: string;
    votes_count: number;
    user: User;
    tags: Tag[];
    votes: [PostVote?];
    slug: string;
}

export interface Comment extends Entity, WithTimestamps {
    body: string;
    votes_count: number;
    user: User;
    votes: [CommentVote?];
}
