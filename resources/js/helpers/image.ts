import { InertiaForm } from '@inertiajs/vue3';

export function handleBannerUpload(event: Event, form: InertiaForm<{ banner: File | null }>) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    form.clearErrors('banner');

    if (!file) {
        form.banner = null;
        return;
    }

    if (!file.type.startsWith('image/')) {
        form.setError('banner', 'Please select a valid image file.');
        return;
    }

    const maxSizeInMB = 2;
    if (file.size > maxSizeInMB * 1024 * 1024) {
        form.setError('banner', `File size cannot exceed ${maxSizeInMB}MB.`);
        return;
    }

    form.banner = file;
}
