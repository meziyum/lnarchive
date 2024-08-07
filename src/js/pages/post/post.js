
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/post/post.scss';
import ReviewSection from '../../Components/ReviewSection.tsx';
import '../common.js';

/* eslint-disable no-undef */
const commentsEnabled = lnarchiveVariables.commentsEnabled;
const commentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;
const reviewsRoot = ReactDOMClient.createRoot(document.getElementById('reviews-section'));
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
const userID = parseInt(lnarchiveVariables.user_id);
/* eslint-enable no-undef */

if (commentsEnabled) {
    reviewsRoot.render(<ReviewSection isLoggedIn={isLoggedIn} userID={userID} loginURL={loginURL} commentType='comment' commentsCount={parseInt(commentsTotalCount)}/>);
}

