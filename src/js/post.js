
//imports
import * as Main from './main.js';
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../sass/post/post.scss';
import Review from './Components/Review.js';
import Review_Section from './Components/Review_Section';

//Localised Constants from Server
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;

//Class Constants
const reviews_root = ReactDOMClient.createRoot(document.getElementById('reviews-section')); //Create the Reviews Root

//Global Page Variables
var is_loggedin = false; //Variable to store user logged in status

fetch( custom_api_request_url+"current_user", { //Fetch current user data
    headers: { //Actions on the HTTP Request
        'X-WP-Nonce' : user_nonce,
    },
}) //Fetch the JSON data
.then( res => res.json()) //The fetch API Response
.then( data => { //The fetch api data
    if( data != false) //If output is returned then the user is logged in
        is_loggedin = true;
    reviews_root.render(<Review_Section is_loggedin={is_loggedin} comment_type='comment'/>); //Render the Review Section
})