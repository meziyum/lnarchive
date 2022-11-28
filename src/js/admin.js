// admin.js

//import './directory';

//Import Styles
import '../sass/admin.scss'

jQuery(document).ready(function() { //If the document is ready. Prevent execution of the js before the document is ready

    if (jQuery("#titlediv").length) { //If Post Title exists

        if( jQuery("input[name=post_title]").val() == '' ){ //Default state of the button
            document.querySelector('#publish').disabled = true; //Disable the Publish Button
        }

        jQuery("input[name=post_title]").keyup( function(){ //Post Title Key Input action
            if( jQuery("input[name=post_title]").val() == '' ){
                document.querySelector('#publish').disabled = true; //Disable the Publish Button
            }
            else {
                document.querySelector('#publish').disabled = false; //Enable the Publish Button
            }
        });
    }
    
    if (jQuery("#series").length) { //If Series Selector exists

        if( jQuery('#series_meta').find(":selected").val() === "none") { //Default state of the button
            document.querySelector('#publish').disabled = true; //Disable the Publish Button
        }

        jQuery('#series').keyup(function() { //Series key input action

            if( jQuery('#series_meta').find(":selected").val() === "none") { //If the selected value is none
                document.querySelector('#publish').disabled = true; //Disable the Publish button
            }
            else{ //Else
                document.querySelector('#publish').disabled = false; //Enable the Publish Button
            }
        });
    }
});


const tx = document.getElementsByTagName("textarea"); //Select all the Textareas

for (let i = 0; i < tx.length; i++) { //Loop through all the textareas
    tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;"); //Set initial height based on the scroll height
    tx[i].addEventListener("input", autoText, false); //Listen to inputs
}

function autoText() { //Auto adjustable textarea
    this.style.height = 0; //Initial height
    this.style.height = (this.scrollHeight) + "px"; //Calculate height
}