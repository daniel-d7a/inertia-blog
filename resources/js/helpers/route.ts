import type { RouteList } from '@custom-types/ziggy-js';
import { Router, route as ziggyRoute } from 'ziggy-js';

type Query = { _query: Record<string, string | number> };
type NoParams = { _no_params: true };
type RouteParams<T> = T extends { params: infer P } ? P : never;
type IsParamRoute<T> = T extends { params: any } ? true : false;
type IsNoParamRoute<T> = T extends NoParams ? true : false;

// 1. Get the global router instance (no arguments)
export function route(): Router;

// 3. Route with parameters (returns string URL)
export function route<T extends keyof RouteList>(
    name: T extends T ? (IsParamRoute<RouteList[T]> extends true ? T : never) : never,
    params: RouteParams<RouteList[T]>,
    absolute?: boolean,
): string;

// 2. Parameterless route
export function route<T extends keyof RouteList>(
    name: T extends T ? (IsNoParamRoute<RouteList[T]> extends true ? T : never) : never,
    params?: Query,
    absolute?: boolean,
): string;

// Implementation
export function route<T extends keyof RouteList>(name?: T, params?: any, absolute?: boolean): string | Router {
    if (!name) return ziggyRoute();
    return ziggyRoute(name, params, absolute);
}

// export type RouteParams<T extends keyof RouteList> = RouteList[T] extends { params: infer P } ? P : never;

// For use in Vue templates
export const $route = route;
