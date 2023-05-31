
export const formatDate = (oldDate: string): string => {
    const year = oldDate.substring(0, 4);
    const monthName = new Intl.DateTimeFormat('en', {month: 'long'}).format(new Date(oldDate.substring(5, 7)));
    const day = oldDate.substring(8, 10);
    return `${day} ${monthName}, ${year}`;
};

export const formatTitle = (oldTitle: string, slice: boolean): string => {
    const elem = document.createElement('div');
    elem.innerHTML = oldTitle;
    const decodedTitle = elem.innerText;
    if (decodedTitle.length>50 && slice) {
        return `${decodedTitle.slice(0, 50)} ...`;
    }
    return decodedTitle;
};

export const escHTML = (unsafeText: string): string => {
    const div = document.createElement('div');
    div.innerText = unsafeText;
    return div.innerHTML;
};
