
import React from 'react';
import PropTypes from 'prop-types';
import Ratings from '../../../Components/Ratings';
import ReadingListPopup from '../../../Components/ReadingListPopup';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {
    faHeart,
    faFireFlameCurved,
    faBookMedical,
    faBell as faBellTrue,
} from '@fortawesome/free-solid-svg-icons';

import {
    faBell as faBellFalse,
}
    from '@fortawesome/free-regular-svg-icons';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const postID = lnarchiveVariables.object_id;
const userNonce = lnarchiveVariables.nonce;
const userSubscription = lnarchiveVariables.user_subscription;
const userRating = lnarchiveVariables.user_rating;
/* eslint-enable no-undef */
const messages = [
    '',
    'Your rating has been submitted!',
    'You have succesfully subscribed to the novel!',
    'You have unsubsribed to the novel!',
];

interface NovelActionsProps {
    isLoggedIn: boolean;
    novelRating: number;
    novelPopularity?: number;
    maxProgress: number;
}

interface ActionStates {
    rating: number;
    displayMessage: boolean;
    currentMessage: number;
    currentSubscriptionStatus: boolean;
    readingListPopupVisible: boolean;
}

/**
A React component that renders actions for a novel, including rating the novel.
@param {Object} props - The component props.
@param {boolean} props.isLoggedIn - Whether the user is currently logged in.
@param {number} props.novelRating - The average rating of the novel.
@param {number} props.novelPopularity - The popularity of the novel.
@return {JSX.Element} - A React component that displays novel actions.
*/
const NovelActions: React.FC<NovelActionsProps> = ({isLoggedIn, novelRating, novelPopularity=0, maxProgress}: NovelActionsProps) => {
    const [actionStates, updateActionStates] = React.useState<ActionStates>({
        rating: userRating == null ? 0 : parseInt(userRating),
        displayMessage: false,
        currentMessage: 0,
        currentSubscriptionStatus: userSubscription,
        readingListPopupVisible: false,
    });

    const submitRatings = (value: number) => {
        if (value != actionStates.rating) {
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
                currentMessage: 1,
                displayMessage: true,
            }));
            messageTimeout();
        }
    };

    const messageTimeout = () => {
        setTimeout(() => {
            updateActionStates( (prevStates) => ({
                ...prevStates,
                displayMessage: false,
            }));
        }, 3000);
    };

    const handleSubscribe = () => {
        updateActionStates( (prevStates) => ({
            ...prevStates,
            currentMessage: prevStates.currentSubscriptionStatus ? 3 : 2,
            currentSubscriptionStatus: !prevStates.currentSubscriptionStatus,
            displayMessage: true,
        }));
        messageTimeout();

        fetch(`${customAPIRequestURL}subscribe`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                object_id: postID,
            }),
        });
    };

    const handleReadingListVisibility = () => {
        updateActionStates((prevInfo) => ({
            ...prevInfo,
            readingListPopupVisible: !prevInfo.readingListPopupVisible,
        }));
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
                    <h3>{novelRating>0 ? `${novelRating}%`: '-'}</h3>
                </div>
                <div id="novel-popularity-div">
                    <FontAwesomeIcon
                        title='Popularity'
                        icon={faFireFlameCurved}
                        size={'xl'}
                        style={{color: '#FF4500'}}
                    />
                    <h3>{novelPopularity ? novelPopularity : 0}</h3>
                </div>
                <div id='end-div'>
                    <FontAwesomeIcon
                        title='Reading Lists'
                        icon={faBookMedical}
                        size={'xl'}
                        style={{color: '#009B77'}}
                        onClick={handleReadingListVisibility}
                    />
                    <FontAwesomeIcon
                        title='Subscribe'
                        icon={actionStates.currentSubscriptionStatus ? faBellTrue : faBellFalse}
                        size={'xl'}
                        style={{color: 'green'}}
                        onClick={handleSubscribe}
                    />
                </div>
            </div>
            <div id="user-ratings">
                {isLoggedIn && <Ratings submitRatings={submitRatings} size={'xl'} rating={(actionStates.rating)/20} mode={'form'}/>}
            </div>
            {actionStates.displayMessage && <h5>{messages[actionStates.currentMessage]}</h5>}
            {actionStates.readingListPopupVisible &&
            <ReadingListPopup maxProgress={maxProgress} handleReadingListVisibility={handleReadingListVisibility}/>
            }
        </>
    );
};
export default NovelActions;

NovelActions.propTypes = {
    isLoggedIn: PropTypes.bool.isRequired,
    novelRating: PropTypes.number.isRequired,
    novelPopularity: PropTypes.number,
    maxProgress: PropTypes.number.isRequired,
};
