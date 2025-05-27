export function getPreviewText(text:string){
    if(text.length > 100) return text.substring(0, 100) + "..."
    return text
}