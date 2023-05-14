
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/profile/profile.scss';

/* eslint-disable no-undef */
const customAPIRequestUrl = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;
const postID = lnarchiveVariables.object_id;
const profileRoot = ReactDOMClient.createRoot(document.getElementById('profile-section'));
let isLoggedIn = true;
/* eslint-enable no-undef */

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
        profileRoot.render();
    });
