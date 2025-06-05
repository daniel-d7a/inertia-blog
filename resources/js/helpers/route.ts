import type { RouteList } from '@custom-types/ziggy-js';
import { Router, route as ziggyRoute } from 'ziggy-js';

type RouteWithParams<T> = T extends { params: infer P } ? P : never;

// 1. Get the global router instance (no arguments)
export function route(): Router;

// 2. Parameterless route (returns Router for chaining)
export function route<T extends keyof RouteList>(name: RouteList[T] extends { _no_params: true } ? T : never): string;

// 3. Route with parameters (returns string URL)
export function route<T extends keyof RouteList>(
    name: RouteList[T] extends { params: any } ? T : never,
    params: RouteWithParams<RouteList[T]>,
    absolute?: boolean,
): string;

// 4. Parameterless route (returns string URL)
export function route<T extends keyof RouteList>(name: RouteList[T] extends never ? T : never, params: never, absolute?: boolean): string;

// Implementation
export function route<T extends keyof RouteList>(name?: T, params?: any, absolute?: boolean): string | Router {
    if (!name) return ziggyRoute();
    return ziggyRoute(name, params, absolute);
}

export type RouteParams<T extends keyof RouteList> = RouteList[T] extends { params: infer P } ? P : never;

// For use in Vue templates
export const $route = route;
