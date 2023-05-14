
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/profile/profile.scss';
import ProfileView from './Components/ProfileView.tsx';

/* eslint-disable no-undef */
const customAPIRequestUrl = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const pageID = lnarchiveVariables.object_id;
const profileRoot = ReactDOMClient.createRoot(document.getElementById('profile-section'));
let isLoggedIn = true;
/* eslint-enable no-undef */

fetch( `${customAPIRequestUrl}current_user/${pageID}`, {
    headers: {
        'X-WP-Nonce': userNonce,
    },
})
    .then( (res) => res.json())
    .then( (data) => {
        if (data.data != undefined && data.data.status == 401) {
            isLoggedIn = false;
        }
        console.log(data);
        profileRoot.render(<ProfileView coverURL={data.coverURL}/>);
    });
