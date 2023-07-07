
import React from 'react';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
/* eslint-enable no-undef */

interface ProfileViewProps {
}

interface ProfileViewStates {
}

const ProfileView: React.FC<ProfileViewProps> = ({}: ProfileViewProps) => {
    return (
        <>
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

ProfileView.propTypes = {
};
