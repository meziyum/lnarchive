
import React from 'react';
import PropTypes, { array } from 'prop-types';
import {createBrowserRouter, RouterProvider} from 'react-router-dom';
import ReadingLists from '../Routes/ReadingLists';
import UserSettings from '../Routes/UserSettings';
import SubscriptionList from '../Routes/SubscriptionList';
import Notifications from '../Routes/Notifications';

/* eslint-disable no-undef */
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
const userID = lnarchiveVariables.user_id;
/* eslint-enable no-undef */
const params = new URLSearchParams(window.location.search);
const profileID = params.get(`list_id`);
const viewOwnProfile = userID == profileID;
const router = createBrowserRouter([
    {
        path: `wordpress/profile/notifications`,
        element: <Notifications/>,
    },
    {
        path: `wordpress/profile/reading-list`,
        element: <ReadingLists/>,
    },
    {
        path: `wordpress/profile/subscription-list`,
        element: <SubscriptionList/>,
    },
    {
        path: `wordpress/profile/settings`,
        element: <UserSettings/>,
    },
]);

interface ProfileViewProps {
}

interface ProfileViewStates {
}

const ProfileView: React.FC<ProfileViewProps> = ({}: ProfileViewProps) => {
    const [profileViewStates, updateProfileViewStates] =React.useState<ProfileViewStates>({
    });

    return (
        <>
            <div className='row profile-main'>
                <div className='col-lg-3 profile-left'>
                    <p>Birthday: 24/02/2003</p>
                    <p>Gender: Male</p>
                    <p>Emeralds: 1350</p>
                    <p>Roles</p>
                </div>
                <div className='col-lg-9 profile-right'>
                    <div id='route-select'>
                        <RouterProvider router={router}/>
                    </div>
                </div>
            </div>
        </>
    );
};

export default ProfileView;

ProfileView.propTypes = {
};
