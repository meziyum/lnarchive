// main.js

//import './directory';

//Import Styles
import '../sass/main.scss'

//Import the Images

var site_url = window.location.origin;
var json_request_url = site_url.concat("/wordpress//wp-json/wp/v2");
var path = window.location.pathname;



var posts=null;

jQuery.ajax({
    type:"GET",
    url: json_url.concat("/posts/"),
    success: function(data) {
            posts = data;
        },
    dataType: "json"
});

console.log(posts);