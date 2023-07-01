
import React from 'react';
import PropTypes from 'prop-types';
import Ratings from '../../../Components/Ratings';
import ReactSelectData from '../../../types/ReactSelectData';
import Select from 'react-select';
import {reactSelectStyle} from '../../../style/reactSelectStyles';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {
    faHeart,
    faXmark,
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

interface readingList {
    list_id: string;
    name: string;
}

interface NovelActionsProps {
    isLoggedIn: boolean;
    novelRating: number;
    novelPopularity?: number;
    readingLists: Array<readingList>;
    maxProgress: number;
}

interface ActionStates {
    rating: number;
    displayMessage: boolean;
    currentMessage: number;
    reading_progress: number;
    novel_status: 'none' | 'plan_to_read' | 'completed' | 'reading' | 'on_hold' | 'dropped';
    currentReadingList: Array<ReactSelectData>;
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
const NovelActions: React.FC<NovelActionsProps> = ({isLoggedIn, novelRating, novelPopularity=0, readingLists, maxProgress}: NovelActionsProps) => {
    const [actionStates, updateActionStates] = React.useState<ActionStates>({
        rating: userRating == null ? 0 : parseInt(userRating),
        displayMessage: false,
        currentMessage: 0,
        reading_progress: 0,
        novel_status: 'none',
        currentReadingList: [],
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

    const updateForm = (event: React.ChangeEvent<HTMLInputElement> | React.ChangeEvent<HTMLSelectElement>) => {
        updateActionStates((prevInfo) => ({
            ...prevInfo,
            [event.target.name]: event.target.value,
        }));
    };

    const updateReadingList = async (event: React.FormEvent<HTMLButtonElement>) => {
        event.preventDefault();
        fetch( `${customAPIRequestURL}reading_list`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                object_id: postID,
                status: actionStates.novel_status,
                progress: actionStates.reading_progress,
            }),
        });
        updateActionStates( (prevStates) => ({
            ...prevStates,
            readingListPopupVisible: false,
        }));
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

    const updateReadingListData = (data: Array<ReactSelectData>) => {
        updateActionStates((prevInfo) => ({
            ...prevInfo,
            currentReadingList: data,
        }));
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
            <form id="reading-list-action" onSubmit={updateReadingList}>
                <div id='cancel-reading-list'>
                    <FontAwesomeIcon
                        title='Cancel'
                        icon={faXmark}
                        size={'2x'}
                        style={{color: 'white'}}
                        onClick={handleReadingListVisibility}
                    />
                </div>
                <div>
                    <label htmlFor="reading_progress">Progress: </label>
                    <input name='reading_progress' id='reading_progress' type='number' value={actionStates.reading_progress} onChange={updateForm} min={0} max={maxProgress}></input>
                </div>
                <div>
                    <label htmlFor="novel_status">Reading Status: </label>
                    <select name="novel_status" id="novel_status" onChange={updateForm}>
                        <option value='none'>None</option>
                        <option value='plan_to_read'>Plan to Read</option>
                        <option value='reading'>Reading</option>
                        <option value='on_hold'>On Hold</option>
                        <option value='completed'>Completed</option>
                        <option value='dropped'>Dropped</option>
                    </select>
                </div>
                <Select
                    placeholder={`Select Reading Lists`}
                    options={
                        readingLists.map((readingList: readingList) => (
                            {
                                value: readingList.list_id,
                                label: readingList.name,
                            }
                        ))
                    }
                    isMulti
                    value={actionStates.currentReadingList}
                    onChange={updateReadingListData}
                    isClearable={true}
                    styles={reactSelectStyle}
                />
                <button id="update-reading-list">Update</button>
            </form>
            }
        </>
    );
};
export default NovelActions;

NovelActions.propTypes = {
    isLoggedIn: PropTypes.bool.isRequired,
    novelRating: PropTypes.number.isRequired,
    novelPopularity: PropTypes.number,
};
