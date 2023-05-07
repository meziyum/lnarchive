
import React from 'react';
import PropTypes from 'prop-types';
import Review from './Review.jsx';
import {escHTML} from '../helpers/utilities.ts';
import InfiniteScroll from '../extensions/InfiniteScroll.js';

/* eslint-disable no-undef */
const postID = lnarchiveVariables.object_id;
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsPerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
 * Represents a section for displaying and submitting reviews or comments for a post.
 * @param {object} props - The component props
 * @param {number} props.commentsCount - The total number of comments for the post
 * @param {boolean} props.isLoggedIn - Indicates if the user is currently logged in
 * @param {number} props.maxProgress - The maximum number of volumes that can be selected when submitting a review
 * @param {number} props.userID - The ID of the currently logged-in user
 * @param {string} props.commentType - The type of comments to display (e.g. "review")
 * @param {string} props.loginURL - The URL for the login page
 * @return {JSX.Element} - The ReviewSection component
 */
export default function ReviewSection(props) {
    const lastResponseLength = React.useRef(0);
    const [sectionInfo, updateSectionInfo] = React.useState({
        commentList: [],
        commentsCount: props.commentsCount,
        currentPage: 1,
        displayInfiniteLoader: true,
        currentSort: 'likes',
        reviewContent: '',
        progress: 0,
    });

    const userID = props.userID;
    const commentType = props.commentType.charAt(0).toUpperCase() + props.commentType.slice(1);

    const fetchComments = async () => {
        try {
            const fields = '&_fields=id,author_name,author,author_avatar_urls,content,date,post,userID,meta,is_logged_in,user_comment_response,rating';

            const res = await fetch( `${wpRequestURL}comments?post=${postID}&orderby=${sectionInfo.currentSort}&per_page=${commentsPerPage}&page=${sectionInfo.currentPage}${fields}`, {
                headers: {
                    'X-WP-Nonce': userNonce,
                },
            });
            const data= await res.json();

            if ( res.status === 200 ) {
                const commentsMap = data.map( (comment) => {
                    return (
                        <Review
                            key={comment.id}
                            isLoggedIn={props.isLoggedIn}
                            userID={userID}
                            deleteReview={deleteReview}
                            maxProgress={props.maxProgress}
                            {...comment}
                        />
                    );
                });
                lastResponseLength.current=commentsMap.length;

                updateSectionInfo( (prevInfo) => ( {
                    ...prevInfo,
                    commentList: prevInfo.currentPage === 1 ? commentsMap : [...prevInfo.commentList, ...commentsMap],
                }));
            }
        } catch (error) {
            lastResponseLength.current=0;
        }
    };

    React.useMemo( function() {
        fetchComments( sectionInfo.currentSort, sectionInfo.currentPage);
    }, [sectionInfo.currentPage, sectionInfo.currentSort, sectionInfo.commentsCount]);

    const submitReview = async (event) => {
        event.preventDefault();

        if (sectionInfo.reviewContent == '') {
            return;
        }

        const res = await fetch( `${customAPIRequestURL}submit_comment`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                content: escHTML(sectionInfo.reviewContent),
                postID: postID,
                progress: sectionInfo.progress,
            }),
        });

        if (res.status === 201) {
            updateSectionInfo( (prevInfo) => ({
                ...prevInfo,
                commentsCount: ++prevInfo.commentsCount,
                currentSort: 'date',
                reviewContent: '',
            }));
        }
    };

    const handleChange = (event) => {
        const {name, value} = event.target;

        updateSectionInfo( (prevInfo) => ({
            ...prevInfo,
            [name]: value,
            currentPage: 1,
        }));
    };

    const deleteReview = async (id) => {
        if ( !window.confirm('Are you sure you want to delete your Review?')) {
            return;
        }

        await fetch( `${wpRequestURL}comments/${id}`, {
            method: 'DELETE',
            headers: {
                'X-WP-Nonce': userNonce,
            },
        });

        updateSectionInfo( (prevInfo) => ({
            ...prevInfo,
            commentsCount: prevInfo.commentsCount-1,
        }));
    };

    const handleInView = () => {
        if (lastResponseLength.current==commentsPerPage) {
            updateSectionInfo( (prevInfo) => ({
                ...prevInfo,
                currentPage: ++prevInfo.currentPage,
            }));
        } else {
            updateSectionInfo( (prevInfo) => ({
                ...prevInfo,
                displayInfiniteLoader: false,
            }));
        }
    };

    return (
        <>
            <h2 id="review-title">{commentType+'s'}</h2>
            {
                props.isLoggedIn ?
                    <form id="reviews-form" onSubmit={submitReview}>
                        <div id="reviews-form-header">
                            <h4>Write your {commentType}</h4>
                            {commentType == 'Review' && props.maxProgress>0 &&
                            <div>
                                <label htmlFor="progress"><h5>No of Volumes(Read)</h5></label>
                                <input type="number" id="progress" name="progress" value={sectionInfo.progress} onChange={handleChange} min="0" max={props.maxProgress}/>
                            </div>
                            }
                        </div>
                        <textarea name="reviewContent" id="reviewContent" onChange={handleChange} value={sectionInfo.reviewContent}/>
                        <div id="reviews-form-footer">
                            <button id="review-submit">Submit</button>
                        </div>
                    </form> :
                    <h3>You need to be <a href={props.loginURL}>logged in</a> to submit a {commentType}</h3>
            }
            {
                sectionInfo.commentsCount>0 &&
                <div id="reviews-filter-header" className="d-flex justify-content-end">
                    <label htmlFor="review-filter" className="me-1">Sort:</label>
                    <select name="currentSort" id="review-filter" onChange={handleChange} value={sectionInfo.currentSort}>
                        {props.isLoggedIn && <option value="author">Your {commentType}s</option>}
                        <option value="likes">Popularity</option>
                        <option value="date">Latest</option>
                        {props.maxProgress >0 && <option value="progress">Progress</option>}
                    </select>
                </div>
            }
            <div id="reviews-list">
                {sectionInfo.commentList}
            </div>
            <InfiniteScroll handleInView={handleInView} displayLoader={sectionInfo.displayInfiniteLoader && sectionInfo.commentsCount>0}/>
        </>
    );
}

ReviewSection.propTypes = {
    commentsCount: PropTypes.number.isRequired,
    isLoggedIn: PropTypes.bool.isRequired,
    maxProgress: PropTypes.number.isRequired,
    userID: PropTypes.number.isRequired,
    commentType: PropTypes.string.isRequired,
    loginURL: PropTypes.string.isRequired,
};

ReviewSection.defaultProps = {
    isLoggedIn: false,
    commentType: 'comment',
    commentsCount: 0,
    maxProgress: 0,
};
