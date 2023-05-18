
import React from 'react';
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {formatDate} from '../helpers/utilities.ts';
import Ratings from './Ratings.jsx';

import {
    faThumbsDown,
    faThumbsUp,
}
    from '@fortawesome/free-regular-svg-icons';

import {
    faThumbsDown as faThumbsDownSolid,
    faThumbsUp as faThumbsUpSolid,
    faEllipsis,
    faChevronDown,
    faChevronUp,
}
    from '@fortawesome/free-solid-svg-icons';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const websiteURL = lnarchiveVariables.websiteURL;
const profileName = lnarchiveVariables.profileName;
const userNonce = lnarchiveVariables.nonce;
/* eslint-enable no-undef */

/**
 * A component that displays a review for a post-type.
 *
 * @param {object} props - The props that are passed into this component.
 * @param {number} props.id - The ID of the review
 * @param {number} props.userID - The ID of the user who wrote the review.
 * @param {boolean} props.isLoggedIn - Indicates whether the user is logged in or not.
 * @param {object} props.content - The content of the review.
 * @param {string} props.content.rendered - The rendered content of the review.
 * @param {object} props.meta - Metadata for the review, such as the number of likes and dislikes.
 * @param {number} props.meta.likes - The number of likes for the review.
 * @param {number} props.meta.dislikes - The number of dislikes for the review.
 * @param {number} props.meta.progress - The progress of how many volumes the user has read
 * @param {Array} props.user_comment_response - The user's response to the review.
 * @param {string} props.author - The id of the author of the review.
 * @param {object} props.author_avatar_urls - The avatar URLs of the author of the review.
 * @param {string} props.author_avatar_urls['96'] - The URL for the author's 96x96 avatar.
 * @param {string} props.author_name - The name of the author of the review.
 * @param {string} props.date - The date that the review was published.
 * @param {number} props.rating - The rating for the book or movie.
 * @param {number} props.maxProgress - The maximum progress for the book or movie.
 * @param {function} props.deleteReview - A function to delete the review.
 *
 * @return {JSX.Element} The JSX code for the component.
 */
export default function Review(props) {
    const userID =props.userID;
    const readMoreLength = 750;
    const contentLong = props.content.rendered;
    const contentShort = props.content.rendered.substring(0, props.content.rendered.substring(0, readMoreLength).lastIndexOf(' '))+'...';

    const [reviewInfo, updateReviewInfo] = React.useState({
        content: contentLong.length <= readMoreLength ? contentLong : contentShort,
        like: props.meta.likes,
        dislike: props.meta.dislikes,
        user_response: props.user_comment_response.length != 0 ? props.user_comment_response[0].response_type : 'none',
        expanded: false,
        editable: false,
    });

    let readMoreButton = null;

    if ( props.content.rendered.length > readMoreLength ) {
        readMoreButton =<a onClick={readMoreClick}>
            <FontAwesomeIcon
                title='Read more'
                icon={ reviewInfo.expanded ? faChevronUp : faChevronDown}
                size="lg"
            />
            Read more
        </a>;
    }

    const updateResponseDatabase = (action) => {
        fetch( `${customAPIRequestURL}comment_${action}/${props.id}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
        });

        const currentResponse = reviewInfo.user_response;

        updateReviewInfo( (prevInfo) => ({
            ...prevInfo,
            [currentResponse]: prevInfo[currentResponse]-1,
            [action != 'none' ? action : '']: prevInfo[action]+1,
            user_response: action,
        }));
    };

    const readMoreClick = () => {
        updateReviewInfo( (prevInfo) => ({
            ...prevInfo,
            content: reviewInfo.expanded ? contentShort : contentLong,
            expanded: !reviewInfo.expanded,
        }));
    };

    const reviewEdit = () => {
        updateReviewInfo( (prevInfo) => ({
            ...prevInfo,
            editable: true,
        }));
    };

    return (
        <div className="row review-entry">
            <div className="review-header row">
                <div className='review-header-left col-3 col-sm-2 col-md-2 col-lg-1 p-0'>
                    <a href={`${websiteURL}/${profileName}`}>
                        <img className="user_avatar float-start rounded-circle" srcSet={props.author_avatar_urls['96']} alt='Author Avatar'/>
                    </a>
                </div>
                <div className='review-header-right col'>
                    <div>
                        <h5>{props.author_name.charAt(0).toUpperCase() + props.author_name.slice(1)}</h5>
                        <h6>{formatDate(props.date.slice(0, props.date.indexOf('T')))}</h6>
                    </div>
                    <div>
                        {props.rating && <Ratings rating={parseInt(props.rating)} mode={'display'} size={'1x'}/>}
                        {props.meta.progress !==0 && <h5>Progress: {props.meta.progress}/{props.maxProgress}</h5>}
                    </div>
                </div>
            </div>
            <div className="review-content" contentEditable={reviewInfo.editable} dangerouslySetInnerHTML={ {__html: reviewInfo.content}}/>
            <div className="d-flex justify-content-center">
                {readMoreButton}
            </div>
            <div className="review-footer">
                <div className="reactions">
                    {
                        reviewInfo.user_response == 'like' ?
                            <FontAwesomeIcon
                                title='Liked'
                                icon={faThumbsUpSolid}
                                size="xl"
                                style={{color: 'limegreen'}}
                                onClick={ () => props.isLoggedIn && updateResponseDatabase('none')}
                            /> :
                            <FontAwesomeIcon
                                title='Like'
                                icon={faThumbsUp}
                                size="xl"
                                style={{color: 'limegreen'}}
                                onClick={ () => props.isLoggedIn && updateResponseDatabase('like')}
                            />
                    }
                    <p>{reviewInfo.like}</p>
                    {
                        reviewInfo.user_response == 'dislike' ?
                            <FontAwesomeIcon
                                title='Disliked'
                                icon={faThumbsDownSolid}
                                size="xl"
                                style={{color: 'crimson'}}
                                onClick={ () => props.isLoggedIn && updateResponseDatabase('none')}
                            /> :
                            <FontAwesomeIcon
                                title='Dislike'
                                icon={faThumbsDown}
                                size="xl"
                                style={{color: 'crimson'}}
                                onClick={ () => props.isLoggedIn && updateResponseDatabase('dislike')}
                            />
                    }
                    <p>{reviewInfo.dislike}</p>
                </div>
                {
                    props.isLoggedIn &&
                    <div className="dropstart">
                        <a id="comment_user_actions" data-bs-toggle="dropdown" aria-expanded="false">
                            <FontAwesomeIcon
                                title='Comment Actions'
                                icon={faEllipsis}
                                size="xl"
                                style={{color: 'grey'}}
                            />
                        </a>
                        <ul className="dropdown-menu" aria-labelledby="comment_user_actions">
                            { userID == props.author && <a className="dropdown-item" onClick={reviewEdit}>Edit</a>}
                            { userID == props.author && <a className="dropdown-item" onClick={ () => props.deleteReview(props.id)}>Delete</a>}
                            <a className="dropdown-item" >Report</a>
                        </ul>
                    </div>
                }
            </div>
        </div>
    );
}

Review.propTypes = {
    id: PropTypes.number.isRequired,
    userID: PropTypes.number.isRequired,
    isLoggedIn: PropTypes.bool.isRequired,
    content: PropTypes.shape({
        rendered: PropTypes.string.isRequired,
    }).isRequired,
    meta: PropTypes.shape({
        likes: PropTypes.number.isRequired,
        dislikes: PropTypes.number.isRequired,
        progress: PropTypes.number.isRequired,
    }).isRequired,
    user_comment_response: PropTypes.array.isRequired,
    author: PropTypes.number.isRequired,
    author_avatar_urls: PropTypes.shape({
        '96': PropTypes.string.isRequired,
    }).isRequired,
    author_name: PropTypes.string.isRequired,
    date: PropTypes.string.isRequired,
    rating: PropTypes.string,
    maxProgress: PropTypes.number,
    deleteReview: PropTypes.func.isRequired,
};

Review.defaultProps = {
    isLoggedIn: false,
    meta: {
        likes: 0,
        dislikes: 0,
        progress: 0,
    },
    maxProgress: 0,
};
