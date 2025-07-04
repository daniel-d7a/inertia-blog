// Auto-generated by Laravel - DO NOT EDIT MANUALLY
declare module '@custom-types/ziggy-js' {
    interface RouteList {
        'sanctum.csrf-cookie': { _no_params: true };
        home: { _no_params: true };
        about: { _no_params: true };
        dashboard: { _no_params: true };
        'blog.index': { _no_params: true };
        'blog.create': { _no_params: true };
        'blog.edit': { params: { post: string | number } };
        'blog.show': { params: { post: string | number } };
        'blog.store': { _no_params: true };
        'blog.destroy': { params: { post: string | number } };
        'blog.update': { params: { post: string | number } };
        'comment.store': { params: { post: string | number } };
        'comment.update': { params: { comment: string | number } };
        'comment.delete': { params: { comment: string | number } };
        'vote.update': { _no_params: true };
        'profile.edit': { _no_params: true };
        'profile.update': { _no_params: true };
        'profile.destroy': { _no_params: true };
        'password.edit': { _no_params: true };
        'password.update': { _no_params: true };
        appearance: { _no_params: true };
        register: { _no_params: true };
        login: { _no_params: true };
        logout: { _no_params: true };
        'storage.local': { params: { path: string | number } };
    }
}
