
import React from 'react';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
/* eslint-enable no-undef */

interface UserSettingsProps {
}

interface UserSettingsStates {
}

const UserSettings: React.FC<UserSettingsProps> = ({}: UserSettingsProps) => {
    const [UserSettingstates, updateUserSettingstates] =React.useState<UserSettingsStates>({

    });
    return (
        <>
            <h1>Reading Lists</h1>
        </>
    );
};

export default UserSettings;

UserSettings.propTypes = {
};
