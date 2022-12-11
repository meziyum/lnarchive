// main.js

//import './directory';

//import images
import mainImage from '../img/test.png';

//Import Styles
import '../sass/main.scss'

//Import the Images

var site_url = window.location.origin;
var json_request_url = site_url.concat("/wordpress/wp-json/wp/v2/");
var path = window.location.pathname;

var slug = path.substring(0, path.length-1);
slug = slug.substring(slug.lastIndexOf("/")+1);

//Get the post_type
var post_type = path.substring(1, path.lastIndexOf("/"));
post_type = post_type.substring(post_type.indexOf("/")+1, post_type.lastIndexOf("/"));

if( post_type == "/")
post_type = "post";

//Get the post id
var post_id = document.querySelector('.main-row').getAttribute('id');

let volumes_list = document.getElementsByClassName("volume-link"); //Get all the volumes of the novel

for( var i=0; i<volumes_list.length; i++){ //Loop through all the volumes
    volumes_list[i].addEventListener('click', function() { //Listen to the click event on the volumes
        fetch(json_request_url+"volumes/"+this.id+"?_embed&_fields=title,excerpt,featured_media,_links") //Fetch the JSON data
            .then( res => res.json()) //The fetch API Response
            .then( data => { //The fetch api data
                document.querySelector(".novel-cover").srcset=data._embedded['wp:featuredmedia']['0'].source_url; //Update the Novel Cover
                document.querySelector(".page-title").innerText = data.title.rendered; //Update the Novel Title
                document.querySelector(".novel-info p").innerHTML = data.excerpt.rendered; //Update the Novel Desc
                
                var novel_terms = data._embedded['wp:term'];

                for( var i=0; i<novel_terms.length; i++){

                }

                console.log(data)
                window.scrollTo(0, 0); //Scroll to the top
            })
      }
    );
}

//Novel Info
var volumes_no = document.getElementById("volume-list").children.length;

document.getElementById("volumes-no").innerText= "Volumes - ".concat(volumes_no)  //Update the number of volumes information