
import React from 'react';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
/* eslint-enable no-undef */

interface SubscriptionListProps {
}

interface SubscriptionListStates {
}

const SubscriptionList: React.FC<SubscriptionListProps> = ({}: SubscriptionListProps) => {
    const [SubscriptionListStates, updateSubscriptionListStates] =React.useState<SubscriptionListStates>({

    });
    return (
        <>
            <h1>Subscriptions List</h1>
        </>
    );
};

export default SubscriptionList;

SubscriptionList.propTypes = {
};
