// import { RouteList } from '@custom-types/ziggy-js';

// declare global {
//     function route(): Router;
//     function route(name: string, params?: RouteParams<typeof name> | undefined, absolute?: boolean): string;
// }

// declare module '@vue/runtime-core' {
//     interface ComponentCustomProperties {
//         $route: {
//             // Without parameters
//             <T extends keyof RouteList>(name: T): Router;
//             // With parameters
//             <T extends keyof RouteList>(name: T, params: RouteList[T] extends { params: infer P } ? P : never, absolute?: boolean): string;
//         };
//     }
// }
