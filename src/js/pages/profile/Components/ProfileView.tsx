
import React from 'react';

interface ProfileViewProps {
    displayName: string;
    coverURL: string;
    bannerURL: string;
    gender: string;
    isLoggedIn: boolean;
}

/**
A React component for displaying a user profile.
@param {object} ProfileViewProps  - The props object.
@param {string} ProfileViewProps.displayName - The display name of the user.
@param {string} ProfileViewProps.coverURL - The URL of the user's profile cover image.
@param {string} ProfileViewProps.bannerURL - The URL of the user's profile banner image.
@param {string} ProfileViewProps.gender - The gender of the user.
@param {boolean} ProfileViewProps.isLoggedIn - A flag indicating whether the user is currently logged in.
@return {JSX.Element} A React element representing the profile banner view.
*/
const ProfileView: React.FC<ProfileViewProps> = ({displayName, coverURL, bannerURL, gender, isLoggedIn}: ProfileViewProps) => {
    return (
        <>
            <div id="header">
                {bannerURL && <img alt='Profile Banner' src={bannerURL}></img>}
                <div id="avatar-div">
                    <img id='avatar' alt='Avatar' src={coverURL}></img>
                    <h2>{displayName}</h2>
                </div>
            </div>
            <div className='row profile-main'>
                <div className='col-lg-3 profile-left'>
                </div>
                <div className='col-lg-9 profile-right'>
                </div>
            </div>
        </>
    );
};

export default ProfileView;
