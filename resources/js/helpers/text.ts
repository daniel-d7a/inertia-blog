export function getPreviewText(text: string, length = 100) {
    if (text.length > length) return text.substring(0, length) + '...';
    return text;
}
