
//Import Libraries

//Import Styles
import '../sass/admin/admin.scss'

const tx = document.getElementsByTagName("textarea"); //Select all the Textareas

for (let i = 0; i < tx.length; i++) { //Loop through all the textareas
    tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;"); //Set initial height based on the scroll height
    tx[i].addEventListener("input", autoText, false); //Listen to inputs
}

function autoText() { //Auto adjustable textarea
    this.style.height = 0; //Initial height
    this.style.height = (this.scrollHeight) + "px"; //Calculate height
}