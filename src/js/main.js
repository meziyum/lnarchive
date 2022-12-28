
//Import Libraries
import bootstrap from 'bootstrap';

//Import Images
import mainImage from '../img/test.png';

//Import Styles
import '../sass/main.scss';

export function esc_html(unsafeText) { //Function to esc html special characters
    let div = document.createElement('div');
    div.innerText = unsafeText;
    return div.innerHTML;
}
