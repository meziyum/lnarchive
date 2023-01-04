
export function format_date( old_date) { //function to format the date
    let year= old_date.substring(0,4); //Get the year from the old date
    let month= Intl.DateTimeFormat('en', { month: 'long' }).format(new Date(parseInt(old_date.substring(5,7)))); //Get the month from old date, convert it to int and then get the equivalent month name using the Intl API
    let day=old_date.substring(8); //get the day from the old date
    return day+' '+month+", "+year; //Returned the merged date
}

export function esc_html(unsafeText) { //Function to esc html special characters
    let div = document.createElement('div');
    div.innerText = unsafeText;
    return div.innerHTML;
}