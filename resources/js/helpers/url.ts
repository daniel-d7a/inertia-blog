
export function createUrlWithParams(baseUrl: string, params: Record<string, string>) {

    // get base url

    // get all params
    const allEmpty = true;
    params = Object.fromEntries(Object.entries(params).filter(([_, val]) => !!val));

    // if all params are empty return base url
    if (!Object.keys(params).length) return baseUrl;

    // else add all params into a params object and add them to url
    const searchParams = new URLSearchParams()
    Object.entries(params).forEach(([key, val]) => {
        searchParams.append(key, val);
    })

    // return full url
    return `${baseUrl}?${searchParams.toString()}`

}