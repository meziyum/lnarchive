
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/profile/profile.scss';
import ProfileView from './Components/ProfileView.tsx';

/* eslint-disable no-undef */
const customAPIRequestUrl = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const profileRoot = ReactDOMClient.createRoot(document.getElementById('profile-section'));
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
/* eslint-enable no-undef */


fetchUserData();

/**
 * Fetches user data from a custom API endpoint and renders a profile view.
 *
 * @async
 * @function fetchUserData
 * @throws {Error} If the response status is not ok.
 * @return {Promise<void>} A Promise that resolves when the user data is fetched and the profile view is rendered.
 */
async function fetchUserData() {
    const response = await fetch( `${customAPIRequestUrl}current_user`, {
        headers: {
            'X-WP-Nonce': userNonce,
        },
    });
    const userData = await response.json();
    console.log(userData);
    profileRoot.render(<ProfileView displayName={userData.displayName} coverURL={userData.coverURL} bannerURL={userData.bannerURL} isLoggedIn={isLoggedIn}/>);
}

