
import React from 'react';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const blogURL = lnarchiveVariables.blogURL;
/* eslint-enable no-undef */

/**
A functional React component that renders a single post item in a blog archive or list view.
@param {object} props - The props object containing the post item data.
@param {string} props.id - The unique identifier of the post item.
@param {string} props.link - The URL link to the full post.
@param {string} props.postImage - The URL link to the post's featured image.
@param {string} props.title - The title of the post.
@param {string} props.date - The date the post was published.
@param {string[]} props.categoryList - An array of category names the post belongs to.
@return {JSX.Element} A JSX element representing a post item.
*/
export default function PostItem(props) {
    return (
        <div className='post-entry-col archive-entry-col col-lg-4 col-md-6 col-sm-12 col-12'>
            <article className='post-entry archive-entry'>
                {props.postImage &&
                    <a id={props.id} className='post-link' href={props.link}>
                        <img className='post-img' src={props.postImage} loading='eager'></img>
                    </a>
                }
                <div className='post-entry-info'>
                    <a href={props.link}><h5 className='entry-title'>{props.title}</h5></a>
                    <h6 className='posted-on'>{props.date}</h6>
                    {
                        props.categoryList.map((category) => (
                            <a key={category} className='category-button anchor-button' href={`${blogURL}/?categories_filter=${category}`}>{category}</a>
                        ))
                    }
                </div>
            </article>
        </div>
    );
}

PostItem.propTypes = {
    id: PropTypes.number.isRequired,
    date: PropTypes.string.isRequired,
    title: PropTypes.string.isRequired,
    link: PropTypes.string.isRequired,
    postImage: PropTypes.string,
    categoryList: PropTypes.array.isRequired,
};

