
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/post/post.scss';
import ReviewSection from '../../Components/ReviewSection.js';

const customAPIRequestUrl = LNarchive_variables.custom_api_url;
const userNonce = LNarchive_variables.nonce;
const commmentsTotalCount = LNarchive_variables.comments_count;
const loginURL = LNarchive_variables.login_url;
const postID = LNarchive_variables.object_id;
const reviewsRoot = ReactDOMClient.createRoot(document.getElementById('reviews-section'));
let isLoggedIn = true;

fetch( `${customAPIRequestUrl}current_user/${postID}`, {
  headers: {
    'X-WP-Nonce': userNonce,
  },
})
    .then( (res) => res.json())
    .then( (data) => {
      if (data.data != undefined && data.data.status == 401) {
        isLoggedIn = false;
      }
      reviewsRoot.render(<ReviewSection is_loggedin={isLoggedIn} login_url={loginURL} comment_type='comment' comments_count={commmentsTotalCount}/>);
    });
