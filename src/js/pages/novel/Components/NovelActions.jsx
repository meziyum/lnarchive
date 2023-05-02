
import React from 'react';
import PropTypes from 'prop-types';
import Ratings from '../../../Components/Ratings.jsx';
/* eslint-disable no-undef */
const postID = lnarchiveVariables.object_id;
const customAPIRequestURL = lnarchiveVariables.wp_rest_url+'lnarchive/v1/';
const userNonce = lnarchiveVariables.nonce;
/* eslint-enable no-undef */

/**

A React component that renders actions for a novel, including rating the novel.
@param {Object} props - The component props.
@param {boolean} props.isLoggedIn - Whether the user is currently logged in.
@param {number} props.rating - The current rating for the novel.
@return {JSX.Element} - A React component that displays novel actions.
*/
export default function NovelActions(props) {
    const [actionStates, updateActionStates] = React.useState({
        rating: props.rating,
    });

    const submitRatings = (value) => {
        fetch( `${customAPIRequestURL}submit_rating/${postID}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                rating: value,
            }),
        });
        updateActionStates( (prevStates) => ({
            ...prevStates,
            rating: value,
        }));
    };

    return (
        <>{props.isLoggedIn && <Ratings ratings_submit={submitRatings} size={'xl'} rating={actionStates.rating} mode={'form'}/>}</>
    );
}

NovelActions.propTypes = {
    isLoggedIn: PropTypes.bool.isRequired,
    rating: PropTypes.number.isRequired,
};

