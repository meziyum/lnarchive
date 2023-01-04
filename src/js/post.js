
//imports
import * as Main from './main.js';
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../sass/post/post.scss';
import Review from './Components/Review.js';
import Review_Section from './Components/Review_Section';

//Localised Constants from Server
const post_id = LNarchive_variables.object_id;
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;
const user_id = LNarchive_variables.user_id;

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
    reviews_display(); //Display the Reviews Section initially with popularity that is likes after the user information has been fetched
})

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
        reviews_root.render(<Review_Section comment_data={comments_map} is_loggedin={is_loggedin} comment_type='comment'/>); //Render the Review Section
    })
}