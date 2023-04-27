
export const formatDate = (oldDate) => {
    const year= oldDate.substring(0, 4);
    const month= Intl.DateTimeFormat('en', {month: 'long'}).format(new Date(parseInt(oldDate.substring(5, 7))));
    const day=oldDate.substring(8);
    return `${day} ${month}, ${year}`;
};

export const escHTML = (unsafeText) => {
    const div = document.createElement('div');
    div.innerText = unsafeText;
    return div.innerHTML;
};
