export function formatDate(date) {
    const dateObject = new Date(date)
    return dateObject.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        // hour: 'numeric',
        // minute: 'numeric',
        // second: 'numeric',
    })
}
export function removeNullKeys(obj) {
    for (const key in obj) {
        if (obj[key] === null) {
            delete obj[key]
        }
    }
}
