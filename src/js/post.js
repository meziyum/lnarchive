
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../sass/post/post.scss';
import Review_Section from './Components/Review_Section.js';

const custom_api_request_url = LNarchive_variables.custom_api_url;
const user_nonce = LNarchive_variables.nonce;
const comments_total_count = LNarchive_variables.comments_count;
const login_url = LNarchive_variables.login_url;
const post_id = LNarchive_variables.object_id;
const reviews_root = ReactDOMClient.createRoot(document.getElementById('reviews-section'));
var is_loggedin = true;

fetch( `${custom_api_request_url}current_user/${post_id}`, {
    headers: {
        'X-WP-Nonce' : user_nonce,
    },
})
.then( res => res.json())
.then( data => {
    if( data.data != undefined && data.data.status == 401)
        is_loggedin = false;
    reviews_root.render(<Review_Section is_loggedin={is_loggedin} login_url={login_url} comment_type='comment' comments_count={comments_total_count}/>);
});