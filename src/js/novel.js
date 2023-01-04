
//Imports
import * as Main from './main.js';
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../sass/novel/novel.scss';
import Review from './Components/Review.js';
import Review_Section from './Components/Review_Section';

const tx = document.getElementsByTagName("textarea"); //Select all the Textareas

for (var i = 0; i < tx.length; i++) { //Loop through all the textareas
    console.log('echo');
    tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;"); //Set initial height based on the scroll height
    tx[i].addEventListener("input", autoText, false); //Listen to inputs
}

function autoText() { //Auto adjustable textarea
    this.style.height = 0; //Initial height
    this.style.height = (this.scrollHeight) + "px"; //Calculate height
}

//Localised Constants from Server
const post_id = LNarchive_variables.object_id;
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;
const user_id = LNarchive_variables.user_id;

//Class Constants
const selected_format_class = 'selected-format';
const format_button_class = 'format-button';
const audiobook_format_class = 'Audiobook-format';
const reviews_root = ReactDOMClient.createRoot(document.getElementById('reviews-section')); //Create the Reviews Root

//Global Page Variables
var selected_format = document.getElementsByClassName(selected_format_class)[0]; //Get the Selected format element
var is_loggedin = false; //Variable to store user logged in status

//Intial Function Calls
narrator_info_display(); //Handle the display of narrator row
formats_click_list( document.getElementsByClassName(format_button_class) ); //Apply click event listeners to initial formats
document.getElementById("volumes-no").innerText= "Volumes - ".concat(document.getElementById("volume-list").children.length)  //Update the number of volumes information
fetch( custom_api_request_url+"current_user", { //Fetch current user data
    headers: { //Actions on the HTTP Request
        'X-WP-Nonce' : user_nonce,
    },
}) //Fetch the JSON data
.then( res => res.json()) //The fetch API Response
.then( data => { //The fetch api data
    if( data != false) //If output is returned then the user is logged in
        is_loggedin = true;
    reviews_display(); //Display the Reviews Section initially with popularity that is likes after the user information has been fetched
})

var volumes_list = document.getElementsByClassName("volume-link"); //Get all the volumes of the novel

//Volumes Information Update Event
for( var i=0; i<volumes_list.length; i++){ //Loop through all the volumes

    volumes_list[i].addEventListener('click', function() { //Listen to the click event on the volumes

        fetch( wp_request_url+"volumes/"+this.id+"?_embed&_fields=title,excerpt,featured_media,_links,meta" ) //Fetch the JSON data
            .then( res => res.json()) //The fetch API Response
            .then( data => { //The fetch api data
                
                document.querySelector(".novel-cover").srcset=data._embedded['wp:featuredmedia']['0'].source_url; //Update the Novel Cover
                document.querySelector(".page-title").innerHTML = data.title.rendered; //Update the Novel Title
                document.getElementById("novel-excerpt").innerHTML = data.excerpt.rendered; //Update the Novel Desc
                
                var novel_terms = data._links['wp:term']; //Get all the taxonomies of the novel

                for( var i=0; i<novel_terms.length; i++) { //Loop through all the taxonomimes

                    var taxonomy_values = data._embedded['wp:term'][i]; //Get Taxonomy terms
                    var element_id = novel_terms[i].taxonomy+"_info_value";
                    const node = document.getElementById(element_id); //Get the taxonomy value parent node

                    if( node == null) //If the node doesnt exist
                    continue;

                    node.innerHTML=""; //Remove all the child elements since new elements will replace them
                    
                    for(var j=0; j<taxonomy_values.length; j++) { //Loop through all the taxonomy terms

                        var tax_name = taxonomy_values[j].name;
                        
                        if( element_id != "format_info_value" ){ //For all other taxonomies except the format
                        const tax_val = document.createElement("a"); //Create an anchor elements
                        tax_val.innerText = tax_name; //Assign the innerText to the anchor tag
                        tax_val.href = taxonomy_values[j].link; //Assign the Link to the anchor tag
                        node.append(tax_val); //Append the anchor tag to the parent node
                        node.append(document.createElement("br")); //Append a line break so the values appear in different lines
                        }
                        else{
                            
                            const tax_val = document.createElement("button"); //Create an anchor elements
                            
                            tax_val.innerText = tax_name; //Assign the insnerText to the anchor tags
                            tax_val.className = tax_name+"-format "+format_button_class; //ASssign classnames to the buttons
                            tax_val.setAttribute( 'isbn', data.meta["isbn_"+tax_name+"_value"][0] ); //Store the ISBN data in the button
                            tax_val.setAttribute( 'publication_date', data.meta["published_date_value_"+tax_name][0] ); //Store the publication date in the button
                            node.append(tax_val); //Append the anchor tag to the parent node

                            if( j==0 ){ //The first format values are treated as default values
                                document.getElementById("ISBN_info_value").innerHTML = "<a>"+data.meta["isbn_"+tax_name+"_value"][0]+"</a>"; //Assign Default ISBN on selection
                                document.getElementById("Publication Date_info_value").innerHTML = "<a>"+data.meta["published_date_value_"+tax_name][0]+"</a>"; //Assign Default Publication Date on selection
                                selected_format = document.getElementsByClassName(tax_name+"-format "+format_button_class)[0]; //Update the selected format global variable
                                selected_format.classList.add(selected_format_class); //Assign the relvant class to the selected variable
                            }
                        }
                    }
                }
                narrator_info_display(); //Handle the display of narrator row
                formats_click_list(document.getElementsByClassName(format_button_class)); //Apply click eventListeners to new formats
                window.scrollTo(0, 0); //Scroll to the top
            })
    }
    );
}

function formats_click_list( format_buttons ) { //Function to apply Event Listeners to all formats and store ISBN and publication values in the format buttons
    for( var i=0; i<format_buttons.length; i++) { //Loop through all the possible format buttons
        format_buttons[i].addEventListener('click', function() { //Listen to click event
            document.getElementById("ISBN_info_value").innerHTML = "<a>"+this.getAttribute("isbn")+"</a>"; //Update the Volume ISBN for format
            document.getElementById("Publication Date_info_value").innerHTML = "<a>"+this.getAttribute("publication_date")+"</a>"; //Update the Volume Publication Date for format
            selected_format.classList.remove(selected_format_class); //Remove the relevant class from the old element
            selected_format = this; //Update the selected_format global var
            selected_format.classList.add(selected_format_class); //Add the relevant class to the element which was clicked that is the selected format
            narrator_info_display(); //Handle the display of narrator row
        });
    }
}

function narrator_info_display() { //Function to handle visibility of the narrator row
    if( document.getElementById(audiobook_format_class) == undefined || selected_format != document.getElementById(audiobook_format_class)) //If the volume of the novel does not have a audiobook format or if the Audiobook formated is not selected
        document.getElementById("Narrator_row").style.display = 'none'; //Hide the Narrator column from view 
    else //If the volume of the novel has audiobook format
        document.getElementById("Narrator_row").style.display = 'table-row'; //Display the Narrator column
}

function reviews_display() { //Function to display the Reviews Section
    fetch( wp_request_url+"comments?post="+post_id+"&orderby=likes&per_page=10&page=1", {
        headers: { //Actions on the HTTP Request
            'X-WP-Nonce' : user_nonce,
        },
    }) //Fetch the comments
    .then( res => res.json()) //Convert the data from Promise to JSON
    .then( data => { //Execut function after data is fetched
        const comments_map = data.map( comment => { //Map the fetched data into a comments list
            return (
                    <Review 
                        key={comment.id} //Map Key
                        is_loggedin={is_loggedin}
                        user_id={user_id}
                        {...comment} //Comment Data
                    />
            );
        });
        console.log(data)
        reviews_root.render(<Review_Section comment_data={comments_map} is_loggedin={is_loggedin} comment_type='review'/>); //Render the Review Section
    })
}