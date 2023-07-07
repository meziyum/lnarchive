
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/profile/profile.scss';
import ProfileView from './Components/ProfileView';

const profileSectionDOM = document.getElementById('profile-section');

if (profileSectionDOM) {
    const profileRoot = ReactDOMClient.createRoot(profileSectionDOM);
    profileRoot.render(<ProfileView/>);
} else {
    new Error('Unable to find the Profile Section DOM');
}
