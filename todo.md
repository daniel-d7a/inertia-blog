## Architecture

[x] split pages down into smaller components.
[x] standerize type for (props, declarations, ...etc).
[ ] split routes into separate files based on resource or path.
[x] define a default layout

## Database

[x] implement basic models (post, user, tag, comment)
[x] implement seeders

## user profile

- user data

    - name

    - displayname
    - image
    - description,
    - links,

    - followers
    - following
    - intrests,
    - dm
    - follow/unfollow button,

    - tag or award
    - threads posts counts,
    - block,

- edit button (user only)

### pages for others

- posts (with count)
- followers (with count)
- following (with count)
- voted posts
- comments
- threads
- awards

### for me

- drafts
- bookmarks
- set theme (database, local storage)
- verify email (add banner to top of home page)
- change password with verification email
- delete account

## UI

[ ] add themes.

## Posts

[x] create basic controller
[x] pagination
[x] show all posts
[x] show single post with comments
[ ] make featured posts
[ ] create more functions in the service
[x] search
[x] get by tag
[ ] featured posts
[x] make post policy for authorization
[x] create post (authorize logged in users)
[x] update post (authorize post owner)
[x] delete post (authorize post owner)
[x] vote post (authorize logged in users)
[ ] authorize admin for all actions
[x] allow adding (predefined) tags to posts (authorize logged in users)
[x] allow creating new tags (authorize logged in users)
[x] search post by title or body
[x] search post by tag
[x] use slugs for posts instead of numeric IDs
[x] add time to read
[x] add update tags to posts
[x] add images to posts

## Post votes

[x] make migration and database table.
[x] configure the seeder and factory with correct relations to post votes.
[x] make controller with vote method. (authorize logged in users)
[x] add optimistic updates to voting.

## Comments

[x] show comments under posts
[x] add comments policy
[x] create comments (authorize logged in users)
[x] update comments (authorize comment owner)
[x] delete comments (authorize comment owner)
[x] vote comments (authorize logged in user)
[ ] create a nested comment section that allows for threads.
[ ] authorize admin for delete comments

## Authintication

[ ] add basic auth
[x] add login
[x] add signup
[ ] add logout
[ ] add forget password
[ ] add account verification

## Extensions

[x] get todo tree (or not)
[x] get formatter (prettier)
[x] get cycle quotes extension.

## Router

[ ] add a welcome page
[ ] add lazy data evaluation and partial reloads
[x] make a typesafe router

## refactoring

[x] refactor the seeder
[ ] remove all unnecessary files and folders
[ ] understand the (already setup) auth system

## random tasks and ideas

[x] extract a tag component
[ ] add type generation from php to typescript (tried and failed)
[x] add rich text input

- add threads of posts (a topic that has multiple posts related to it)
- add user profile.
    - user preferences
    - user posts
    - user votes
    - user comments
    - user subscriptions
- add a notification system on (votes, comments) on your posts
- add a follow mechanism where you can follow users, threads, and tags
- add a forum (????)
    - similar to reddit or stack overflow
    - people ask questions and get answers in the comments
    - best answers get to the top
- add a newsletter with the best articles of the week
- add an automated weekly article with the best topics of the week
- add images (inside posts, photo\gif comments, article cover photo ...etc.)
- add AI features
    - summerization.
    - auto generate title
    - auto select tags.
- turn on SSR (prefetching and preloading as well).
- add SSG (if possible, where possible).
    - try to generate static pages periodically
- add a recommender system (AI) to show articles based on user interactions and subscriptions
- try to monitize it
    - subscriptions.
    - paid articles.
    - premium accounts.
    - boosted posts.
    - paid awards for posts.
- think of a way to compensate writers based on thier performance.
- add view transitions ✨
- add ssr feed.
- add og, twitter, facebook, and other SEO tags
- add analytics.
- learn from dev.to project and take notes.
- add locallization
- allow importing markdown files (in a specific format).
- add images to users
- add a toast for actions
- make an admin dashboard

## questions

- how to create a model that is related to 2 other models (postVote, comment, ...etc)
    - polymorphic relations
- how to create models related to the current auth user without setting the user id manually
- using forward and backward buttons uses a cached version of the pages, while url routing gets a fresh copy
- how to create models in bulk using eloquent
- how to redirect to the route I was on before e.g. logging in?

- how to use generics in php ???????????????

I am going to make this blog the biggest and the greatest arabic blog around.
People are going to come here to find what they are looking for.
I will make it on par with what we use in english (dev.to, daily.dev, medium, ...etc.)
