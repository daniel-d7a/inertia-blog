import { CustomProps } from "@/types/AppTypes";
import { usePage } from "@inertiajs/vue3";

export function useTypedPage<T = unknown>(){
    return usePage<CustomProps<T>>();
}