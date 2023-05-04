
import React from 'react';
import PropTypes from 'prop-types';
import Ratings from '../../../Components/Ratings.jsx';
/* eslint-disable no-undef */
const postID = lnarchiveVariables.object_id;
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
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
        ratingSubmitted: false,
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
            ratingSubmitted: true,
        }));

        setTimeout(() => {
            updateActionStates( (prevStates) => ({
                ...prevStates,
                ratingSubmitted: false,
            }));
        }, 3000);
    };

    return (
        <div id="ratings-div">{props.isLoggedIn && <Ratings ratings_submit={submitRatings} size={'xl'} rating={actionStates.rating} mode={'form'}/>}{actionStates.ratingSubmitted && <h6>Your rating has been submitted!</h6>}</div>
    );
}

NovelActions.propTypes = {
    isLoggedIn: PropTypes.bool.isRequired,
    rating: PropTypes.number.isRequired,
};

