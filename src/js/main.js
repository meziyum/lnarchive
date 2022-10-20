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

console.log(json_request_url+post_type+"s/"+post_id);

var ancestor = document.getElementsByClassName('volume-list');

console.log(post_type)

/*
for (const ans of ancestor[0].children) {
    var id = ans.getAttribute('id')
    console.log(id)
}*/

/*
var post = new wp.api.models.Post( { id: post_id} )
post.fetch()
console.log(post) */

fetch(json_request_url+post_type+"s/"+post_id)
    .then((data) => data.json())
    .then((success) => console.log(success))
    .catch((err) => console.log(err))

async function loadNames(post_id, post_type) {

    var url = json_request_url.concat(post_type,"s/",post_id);

    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
          }
    })
    const names = await response.json();
    // logs [{ name: 'Joker'}, { name: 'Batman' }]
    return names;
}
console.log(loadNames(post_id, post_type));