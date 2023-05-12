
import React from 'react';
import PropTypes from 'prop-types';
import Ratings from '../../../Components/Ratings.jsx';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {
    faHeart,
    faFireFlameCurved,
} from '@fortawesome/free-solid-svg-icons';

/* eslint-disable no-undef */
const postID = lnarchiveVariables.object_id;
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
/* eslint-enable no-undef */

/**

A React component that renders actions for a novel, including rating the novel.
@param {Object} props - The component props.
@param {boolean} props.isLoggedIn - Whether the user is currently logged in.
@param {number} props.novelRating - The average rating of the novel.
@param {number} props.novelPopularity - The popularity of the novel.
@param {number} props.userRating - The current rating for the novel by the user.
@return {JSX.Element} - A React component that displays novel actions.
*/
export default function NovelActions(props) {
    const [actionStates, updateActionStates] = React.useState({
        rating: props.userRating,
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
        <>
            <div id="actions-main">
                <div id="novel-ratings-div">
                    <FontAwesomeIcon
                        title='Ratings'
                        icon={faHeart}
                        size={'xl'}
                        style={{color: '#007bff'}}
                    />
                    <h3>{props.novelRating}%</h3>
                </div>
                <div id="novel-popularity-div">
                    <FontAwesomeIcon
                        title='Popularity'
                        icon={faFireFlameCurved}
                        size={'xl'}
                        style={{color: '#FF4500'}}
                    />
                    <h3>0</h3>
                </div>
                <div id="user-ratings">
                    {props.isLoggedIn && <Ratings title='Your Ratings' ratings_submit={submitRatings} size={'xl'} rating={actionStates.rating} mode={'form'}/>}
                </div>
            </div>
            {actionStates.ratingSubmitted && <h6>Your rating has been submitted!</h6>}
        </>
    );
}

NovelActions.propTypes = {
    isLoggedIn: PropTypes.bool.isRequired,
    novelRating: PropTypes.number.isRequired,
    userRating: PropTypes.number,
    novelPopularity: PropTypes.number,
};

NovelActions.defaultProps = {
    userRating: 0,
    novelPopularity: 0,
};

