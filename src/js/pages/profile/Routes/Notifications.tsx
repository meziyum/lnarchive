
import React from 'react';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
/* eslint-enable no-undef */

interface NotificationsProps {
}

interface NotificationsStates {
}

const Notifications: React.FC<NotificationsProps> = ({}: NotificationsProps) => {
    const [notificationStates, updateNotificationStates] =React.useState<NotificationsStates>({

    });
    return (
        <>
            <h1>Notifications</h1>
        </>
    );
};

export default Notifications;

Notifications.propTypes = {
};
