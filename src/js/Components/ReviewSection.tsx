
import React from 'react';
import PropTypes from 'prop-types';
import Review from './Review.tsx';
import {escHTML} from '../helpers/utilities.ts';
import InfiniteScroll from '../extensions/InfiniteScroll.js';
import commentType from '../types/commentType.js'

/* eslint-disable no-undef */
const postID = lnarchiveVariables.object_id;
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsPerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

interface ReviewSectionType{
    commentsCount?: number,
    isLoggedIn?: boolean,
    maxProgress?: number,
    userID: number,
    commentType?: string,
    loginURL: string,
}

interface sectionInfoType {
    commentList: Array<React.JSX.Element>,
    commentsCount: number,
    currentPage: number,
    displayInfiniteLoader: boolean,
    currentSort: string,
    reviewContent: string,
    progress: number,
}

/**
 * Represents a section for displaying and submitting reviews or comments for a post.
 * @param {object} props - The component props
 * @param {number} commentsCount - The total number of comments for the post
 * @param {boolean} isLoggedIn - Indicates if the user is currently logged in
 * @param {number} maxProgress - The maximum number of volumes that can be selected when submitting a review
 * @param {number} userID - The ID of the currently logged-in user
 * @param {string} commentType - The type of comments to display (e.g. "review")
 * @param {string} loginURL - The URL for the login page
 * @return {JSX.Element} - The ReviewSection component
 */
export default function ReviewSection({commentsCount=0, isLoggedIn=false, maxProgress=0, userID, commentType='comment', loginURL}: ReviewSectionType) {
    const lastResponseLength = React.useRef(0);
    const [sectionInfo, updateSectionInfo] = React.useState<sectionInfoType>({
        commentList: [],
        commentsCount: commentsCount,
        currentPage: 1,
        displayInfiniteLoader: commentsCount>0 ? true : false,
        currentSort: 'likes',
        reviewContent: '',
        progress: 0,
    });

    const CommentType = commentType.charAt(0).toUpperCase() + commentType.slice(1);

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
                const commentsMap = data.map( (comment: commentType) => {
                    return (
                        <Review
                            key={comment.id}
                            isLoggedIn={isLoggedIn}
                            userID={userID}
                            deleteReview={deleteReview}
                            maxProgress={maxProgress}
                            {...(comment as commentType)}
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
        fetchComments();
    }, [sectionInfo.currentPage, sectionInfo.currentSort, sectionInfo.commentsCount]);

    const submitReview = async (event: React.FormEvent<HTMLFormElement>) => {
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
                progress: sectionInfo.progress>maxProgress ? maxProgress : sectionInfo.progress,
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

    const handleChange = (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const {name, value} = event.target;

        updateSectionInfo( (prevInfo) => ({
            ...prevInfo,
            [name]: value,
            currentPage: 1,
        }));
    };

    const deleteReview = async (id: number) => {
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
            <h2 id="review-title">{CommentType+'s'}</h2>
            {
                isLoggedIn ?
                    <form id="reviews-form" onSubmit={submitReview}>
                        <div id="reviews-form-header">
                            <h4>Write your {CommentType}</h4>
                            {CommentType == 'Review' && maxProgress>0 &&
                            <div>
                                <label htmlFor="progress"><h5>Progress</h5></label>
                                <input type="number" id="progress" name="progress" value={sectionInfo.progress} onChange={handleChange} min="0" max={maxProgress}/>
                            </div>
                            }
                        </div>
                        <textarea name="reviewContent" id="reviewContent" onChange={handleChange} value={sectionInfo.reviewContent}/>
                        <div id="reviews-form-footer">
                            <button id="review-submit">Submit</button>
                        </div>
                    </form> :
                    <h3>You need to be <a href={loginURL}>logged in</a> to submit a {CommentType}</h3>
            }
            {
                sectionInfo.commentsCount>0 &&
                <div id="reviews-filter-header" className="d-flex justify-content-end">
                    <label htmlFor="review-filter" className="me-1">Sort:</label>
                    <select name="currentSort" id="review-filter" onChange={handleChange} value={sectionInfo.currentSort}>
                        {isLoggedIn && <option value="author">Your {CommentType}s</option>}
                        <option value="likes">Popularity</option>
                        <option value="date">Latest</option>
                        {maxProgress >0 && <option value="progress">Progress</option>}
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
    commentsCount: PropTypes.number,
    isLoggedIn: PropTypes.bool,
    maxProgress: PropTypes.number,
    userID: PropTypes.number.isRequired,
    commentType: PropTypes.string,
    loginURL: PropTypes.string.isRequired,
};