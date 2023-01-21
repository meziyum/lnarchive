
//Imports
import * as Main from './main.js';
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../sass/novel/novel.scss';
import Review_Section from './Components/Review_Section';
import Novel_Actions from './Components/Novel_Actions.js';

//Localised Constants from Server
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;
const comments_total_count = LNarchive_variables.comments_count;
const login_url = LNarchive_variables.login_url;
const novel_id = LNarchive_variables.object_id;

//Class Constants
const selected_format_class = 'selected-format';
const format_button_class = 'format-button';
const audiobook_format_class = 'Audiobook-format';
const reviews_root = ReactDOMClient.createRoot(document.getElementById('reviews-section')); //Create the Reviews Root
const novel_actions_root = ReactDOMClient.createRoot(document.getElementById('novel-actions'));

//Global Page Variables
var selected_format = document.getElementsByClassName(selected_format_class)[0]; //Get the Selected format element
var is_loggedin = false; //Variable to store user logged in status

//Intial Function Calls
narrator_info_display(); //Handle the display of narrator row
formats_click_list( document.getElementsByClassName(format_button_class) ); //Apply click event listeners to initial formats
if( document.getElementById("volumes-no") != null)
    document.getElementById("volumes-no").innerText= "Volumes - ".concat(document.getElementById("volume-list").children.length)  //Update the number of volumes information

fetch( `${custom_api_request_url}current_user/${novel_id}`, { //Fetch current user data
    headers: { //Actions on the HTTP Request
        'X-WP-Nonce' : user_nonce,
    },
}) //Fetch the JSON data
.then( res => res.json()) //The fetch API Response
.then( data => { //The fetch api data
    if( data != false) //If output is returned then the user is logged in
        is_loggedin = true;
    novel_actions_root.render(<Novel_Actions is_loggedin={is_loggedin} rating={parseInt(data.user_rating)}/>); //Render the novel actions
    reviews_root.render(<Review_Section is_loggedin={is_loggedin} user_id={data.user_id} login_url={login_url} comment_type='review' comments_count={comments_total_count} max_progress={document.getElementById("volume-list").children.length}/>); //Render the Review Section
    console.log(data);
});


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