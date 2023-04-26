
import * as Utilities from '../helpers/utilities.js';
import React from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import Ratings from './Ratings.js';

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

const custom_api_request_url = LNarchive_variables.custom_api_url;
const user_nonce = LNarchive_variables.nonce;

export default function Review(props) {

    let user_id =props.user_id;
    let is_loggedin=props.is_loggedin;
    let read_more_length = 750;
    let content_long = props.content.rendered;
    let content_short = props.content.rendered.substring(0, props.content.rendered.substring(0,read_more_length).lastIndexOf(" "))+"...";

    const [ review_info, update_review_info] = React.useState({
        content: content_long.length <= read_more_length ? content_long : content_short,
        like: props.meta.likes,
        dislike: props.meta.dislikes,
        user_response: props.user_comment_response.length != 0 ? props.user_comment_response[0].response_type : 'none',
        expanded: false,
        editable: false,
    });

    let read_more_button = null;

    if( props.content.rendered.length > read_more_length ){
        read_more_button =  <a onClick={read_more_click}>
                                <FontAwesomeIcon 
                                    icon={ review_info.expanded ? faChevronUp : faChevronDown}
                                    size="lg"
                                />
                                Read more
                            </a>
    }

    function update_response_in_database( action ){
        fetch( `${custom_api_request_url}comment_${action}/${props.id}`, {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
        })

        var current_response = review_info.user_response;

        update_review_info( prev_info => ({
            ...prev_info,
            [current_response]: prev_info[current_response]-1,
            [action != 'none' ? action : '']: prev_info[action]+1,
            user_response: action,
        }));
    }

    function read_more_click(){
        update_review_info( prev_info => ({
            ...prev_info,
            content: review_info.expanded ? content_short : content_long,
            expanded: !review_info.expanded,
        }))
    }

    function review_edit(){
        update_review_info( prev_info => ({
            ...prev_info,
            editable: true,
        }));
    }

    return(
        <div className="row review-entry mb-3">
            <div className="review-header row p-2">
                    <div className='col-3 col-sm-2 col-md-2 col-lg-1 p-0'>
                        <img className="user_avatar float-start rounded-circle" srcSet={props.author_avatar_urls['96']}></img>
                    </div>     
                    <div className='col'>
                        <h4>{props.author_name.charAt(0).toUpperCase() + props.author_name.slice(1)}
                        <div className="float-end">
                            <Ratings rating={parseInt(props.rating)} mode={'display'} size={'1x'}/>
                        </div>
                        </h4>
                        <time>{Utilities.format_date(props.date.slice(0, props.date.indexOf('T')))}</time>
                        <div className="float-end">
                            <h5>Progress: {props.meta.progress}/{props.max_progress}</h5>
                        </div>
                    </div>     
            </div>
                <div className="review-content" contentEditable={review_info.editable} dangerouslySetInnerHTML={ {__html: review_info.content}}/>
            <div className="d-flex justify-content-center">
                {read_more_button}
            </div>
            <div className="review-footer">
                <div className='float-start d-flex'>
                { 
                    review_info.user_response == 'like'
                    ? 
                    <FontAwesomeIcon 
                        icon={faThumbsUpSolid} 
                        size="xl" 
                        style={{ color: 'limegreen' }}
                        onClick={ () => is_loggedin && update_response_in_database('none')}
                    />
                    : 
                    <FontAwesomeIcon
                        icon={faThumbsUp}
                        size="xl" 
                        style={{ color: 'limegreen' }} 
                        onClick={ () => is_loggedin && update_response_in_database('like')}
                    />
                }
                <p>{review_info.like}</p>
                { 
                    review_info.user_response == 'dislike'
                    ? 
                    <FontAwesomeIcon 
                        icon={faThumbsDownSolid} 
                        size="xl" 
                        style={{ color: 'crimson' }}
                        onClick={ () => is_loggedin && update_response_in_database('none')}
                    />
                    :
                    <FontAwesomeIcon 
                        icon={faThumbsDown} 
                        size="xl" 
                        style={{ color: 'crimson' }} 
                        onClick={ () => is_loggedin && update_response_in_database('dislike')}
                    />
                }
                <p>{review_info.dislike}</p>
                </div>
                {
                    is_loggedin
                    &&
                    <div className="float-end dropstart">
                        <a id="comment_user_actions" data-bs-toggle="dropdown" aria-expanded="false">
                            <FontAwesomeIcon 
                                icon={faEllipsis} 
                                size="xl" 
                                style={{ color: 'grey' }}
                            />
                        </a>
                        <ul className="dropdown-menu" aria-labelledby="comment_user_actions">
                            { user_id == props.author && <a className="dropdown-item" onClick={review_edit}>Edit</a>}
                            { user_id == props.author && <a className="dropdown-item" onClick={ () => props.delete_review(props.id)}>Delete</a>}
                            <a className="dropdown-item" >Report</a>
                        </ul>
                    </div>
                }                 
            </div>
        </div>
    );
}