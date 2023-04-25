
export function format_date( old_date) {
    let year= old_date.substring(0,4);
    let month= Intl.DateTimeFormat('en', { month: 'long' }).format(new Date(parseInt(old_date.substring(5,7))));
    let day=old_date.substring(8);
    return day+' '+month+", "+year;
}

export function esc_html(unsafeText) {
    let div = document.createElement('div');
    div.innerText = unsafeText;
    return div.innerHTML;
}