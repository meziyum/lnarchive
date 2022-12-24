
//get the Window information
var site_url = window.location.origin;
var json_request_url = site_url.concat("/wordpress/wp-json/wp/v2/");
var path = window.location.pathname;
var slug = path.substring(0, path.length-1);
slug = slug.substring(slug.lastIndexOf("/")+1);

//Get the post information
var post_type = "novel";
var post_id = document.querySelector('.main-row').getAttribute('id');

function Comment_card( props ){
    return(
        <div className="row">
            <div className="review-left col-3 col-sm-2 col-md-2 col-lg-1">
                <img className="user_avatar" srcset={props.author_avatar}></img>
            </div>
            <div className="review-right col">
                <div className="review-header row">
                    <h4 className="col-lg-9">{props.author_name.charAt(0).toUpperCase() + props.author_name.slice(1)}</h4>
                    <time className="col">{props.date.slice(0, props.date.indexOf('T'))}</time>
                </div>
                <div className="review-content" dangerouslySetInnerHTML={{__html: props.content}}/>
                <div className="review-footer">
                        <i class="fa-solid fa-thumbs-up"></i>
                        <i class="fa-solid fa-thumbs-down"></i>     
                </div>
            </div>
        </div>
    );
}

const comments_div = document.getElementById("reviews-list");

fetch( json_request_url+"comments?post="+post_id )
    .then( res => res.json())
    .then( data => {
        console.log(post_id)
        console.log(data)

        for( var i=0; i<data.length; i++){
            var comment_entry = document.createElement("div");
            comment_entry.className="review-entry"
            comment_entry.id=data[i].id;
            document.getElementById("reviews-list").append(comment_entry);
            console
            ReactDOM.render( <Comment_card comment_id={data[i].id} author_avatar={data[i].author_avatar_urls['96']} author_name={data[i].author_name} date={data[i].date} content={data[i].content.rendered}></Comment_card>, comment_entry );
        }
    })


var selected_format = document.getElementsByClassName("selected_format")[0]; //Get the Selected format element
   
narrator_info_display(); ////Handle the display of narrator row
formats_click_list( document.getElementsByClassName("format_button") ); //Apply click event listeners to initial formats

/*
    Volumes Information Update
*/

let volumes_list = document.getElementsByClassName("volume-link"); //Get all the volumes of the novel

for( var i=0; i<volumes_list.length; i++){ //Loop through all the volumes
    console.log("click")

    volumes_list[i].addEventListener('click', function() { //Listen to the click event on the volumes

        fetch( json_request_url+"volumes/"+this.id+"?_embed&_fields=title,excerpt,featured_media,_links,meta" ) //Fetch the JSON data
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
                            tax_val.className = tax_name+"_format format_button"; //ASssign classnames to the buttons
                            tax_val.setAttribute( 'isbn', data.meta["isbn_"+tax_name+"_value"][0] ); //Store the ISBN data in the button
                            tax_val.setAttribute( 'publication_date', data.meta["published_date_value_"+tax_name][0] ); //Store the publication date in the button
                            node.append(tax_val); //Append the anchor tag to the parent node

                            if( j==0 ){ //The first format values are treated as default values
                                document.getElementById("ISBN_info_value").innerHTML = "<a>"+data.meta["isbn_"+tax_name+"_value"][0]+"</a>"; //Assign Default ISBN on selection
                                document.getElementById("Publication Date_info_value").innerHTML = "<a>"+data.meta["published_date_value_"+tax_name][0]+"</a>"; //Assign Default Publication Date on selection
                                selected_format = document.getElementsByClassName(tax_name+"_format format_button")[0]; //Update the selected format global variable
                                selected_format.classList.add("selected_format"); //Assign the relvant class to the selected variable
                            }
                        }
                    }
                }
                narrator_info_display(); //Handle the display of narrator row
                formats_click_list(document.getElementsByClassName("format_button")); //Apply click eventListeners to new formats
                window.scrollTo(0, 0); //Scroll to the top
            })
    }
    );
} 

document.getElementById("volumes-no").innerText= "Volumes - ".concat(document.getElementById("volume-list").children.length)  //Update the number of volumes information

function formats_click_list( format_buttons ) { //Function to apply Event Listeners to all formats and store ISBN and publication values in the format buttons
    for( var i=0; i<format_buttons.length; i++) { //Loop through all the possible format buttons
        format_buttons[i].addEventListener('click', function() { //Listen to click event
            document.getElementById("ISBN_info_value").innerHTML = "<a>"+this.getAttribute("isbn")+"</a>"; //Update the Volume ISBN for format
            document.getElementById("Publication Date_info_value").innerHTML = "<a>"+this.getAttribute("publication_date")+"</a>"; //Update the Volume Publication Date for format
            selected_format.classList.remove("selected_format"); //Remove the relevant class from the old element
            selected_format = this; //Update the selected_format global var
            selected_format.classList.add("selected_format"); //Add the relevant class to the element which was clicked that is the selected format
            narrator_info_display(); //Handle the display of narrator row
        });
    }
}

function narrator_info_display() { //Function to handle visibility of the narrator row
    if( document.getElementsByClassName("Audiobook_format")[0] != undefined && document.getElementsByClassName("Audiobook_format")[0].classList[2] != "selected_format") //If the volume of the novel does not have a audiobook format or if the Audiobook formated is not selected
        document.getElementById("Narrator_row").style.display = 'none'; //Hide the Narrator column from view     
    else //If the volume of the novel has audiobook format
        document.getElementById("Narrator_row").style.display = 'table-row'; //Display the Narrator column
}