
import React from 'react';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
/* eslint-enable no-undef */

interface EditProfileProps {
}

interface EditProfileStates {
}

const EditProfile: React.FC<EditProfileProps> = ({}: EditProfileProps) => {
    const [editProfileStates, updateEditProfileStates] =React.useState<EditProfileStates>({

    });
    return (
        <>
            <h1>Edit Profile Page</h1>
        </>
    );
};

export default EditProfile;

EditProfile.propTypes = {
};
