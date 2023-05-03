
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Renders a single post item.
 * @param {Object} props - The props object for the component.
 * @return {JSX.Element} - The rendered component.
 */
export default function PostItem(props) {
    console.log(props)
    
    return (
        <div className='post-entry-col archive-entry-col col-lg-4 col-md-6 col-sm-12 col-12'>
            <article className='post-entry archive-entry'>
                <a id={props.id} className='post-link' href={props.link}>
                    <img className='post-img' src={props.postImage} loading='lazy'></img>
                </a>
                <div className='post-entry-info'>
                    <a href={props.link}><h5 className='entry-title'>{props.title}</h5></a>
                    <h6 className='posted-on'>{props.date}</h6>
                    {
                        props.categoryList.map(category => (
                            <a key={category} className='category-button anchor-button'>{category}</a>
                        ))
                    }
                </div>
            </article>
        </div>
    );
}
