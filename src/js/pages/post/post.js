
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/post/post.scss';
import ReviewSection from '../../Components/ReviewSection.js';

const customAPIRequestUrl = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commmentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;
const postID = lnarchiveVariables.object_id;
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
      reviewsRoot.render(<ReviewSection isLoggedIn={isLoggedIn} userID={data.user_id} loginURL={loginURL} commentType='comment' commentsCount={commmentsTotalCount}/>);
    });
