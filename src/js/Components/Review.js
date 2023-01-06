
//Imports
import * as Utilities from '../utilities';
import React from 'react';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

//import regular Fontawesome icons
import  {
    faThumbsDown , 
    faThumbsUp,
    } 
from '@fortawesome/free-regular-svg-icons';

//Import solid Fontawesome icons
import  {    
    faThumbsDown as faThumbsDownSolid,
    faThumbsUp as faThumbsUpSolid,
    faEllipsis,
    faChevronDown,
    faChevronUp
    } 
from '@fortawesome/free-solid-svg-icons';

//Constant Variables from server side
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;

export default function Review( props ){ //Review Entry React Component

    let user_id =props.user_id; //user id
    let is_loggedin=props.is_loggedin; //isloggedin status
    let read_more_length = 100; //Minimum characters to show read more button
    let content_long = props.content.rendered; //Full comment content rendered
    let content_short = props.content.rendered.substring(0, props.content.rendered.substring(0,read_more_length).lastIndexOf(" "))+"..."; //Commment content of read_more_length

    const [ review_info, update_review_info] = React.useState({ //All Review States
        content: content_long <= read_more_length ? content_long : content_short, //State for current loaded comment content
        like: props.meta.likes, //State for current number of likes
        dislike: props.meta.dislikes, //State for current number of dislikes
        user_response: props.user_comment_response.length != 0 ? props.user_comment_response[0].response_type : 'none', //State for current user response to the commment
        visible: true, //State for the comment visibility
        expanded: false, //State for the expanded status of read more that is all content is visible or not
        editable: false, //State for if the comment is editable
    });

    let read_more_button = null; //Read more button JSX

    if( props.content.rendered.length > read_more_length ){ //Render the Read more button if the current comment content state has length more than read_more_length
        read_more_button =  <a onClick={read_more_click}>
                                    <FontAwesomeIcon 
                                        icon={ review_info.expanded ? faChevronUp : faChevronDown}
                                        size="lg"
                                    />
                                    Read more
                                </a>
    }

    function update_response_in_database( action ){ //Function to update the user response
        fetch( custom_api_request_url+'comment/'+action+'/'+props.id, {
            method: "POST", //Method
            credentials: 'same-origin', //Send Credentials
            headers: { //Actions on the HTTP Request
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
        }) //Comment Action API Request

        let current_response = review_info.user_response; //The current response of the user to the comment

        if( current_response == 'like' ){
            if( action == 'dislike'){ //Change user response from dislike to like
                update_review_info( prev_info => ({
                    ...prev_info,
                    like: --prev_info.like,
                    dislike: ++prev_info.dislike,
                }));
            }
            else if(action == 'none') //remove a like
                update_review_info( prev_info => ({
                    ...prev_info,
                    like: --prev_info.like,
                }));        
        }
        else if( current_response == 'dislike' ) {
            if( action == 'like'){ //Change user response from like to dislike
                update_review_info( prev_info => ({
                    ...prev_info,
                    like: ++prev_info.like,
                    dislike: --prev_info.dislike,
                }));
            }
            else if(action == 'none') //remove a dislike
                update_review_info( prev_info => ({
                    ...prev_info,
                    dislike: --prev_info.dislike,
                }));
        }
        else{
            if( action == 'like'){ //like a comment
                update_review_info( prev_info => ({ 
                    ...prev_info,
                    like: ++prev_info.like,
                }));
            }
            else if(action == 'dislike') //dislike a comment
                update_review_info( prev_info => ({
                    ...prev_info,
                    dislike: ++prev_info.dislike,
                }));
        }

        update_review_info( prev_info => ({ //update user current respones to the comment
            ...prev_info,
            user_response: action,
        }));
    }

    function delete_review() { //Delete Review function

        if( !window.confirm("Are you sure you want to delete your Review?") ) //If the user doesnt click OK
            return;

        fetch( wp_request_url+"comments/"+props.id, {
            method: "DELETE", //Method
            headers: { //Actions on the HTTP Request
                'X-WP-Nonce' : user_nonce,
            },
        }) //Fetch the comments

        update_review_info( prev_info => ({ //Remove comment visibility so the comment appears deleted
            ...prev_info,
            visible: false,
        }));
    }

    function read_more_click(){ //Read more button click function
        update_review_info( prev_info => ({ //Update the rendered content and expanded status of the review
            ...prev_info,
            content: review_info.expanded ? content_short : content_long,
            expanded: !review_info.expanded,
        }))
    }

    function review_edit(){ //Enable review edit function
        update_review_info( prev_info => ({ //Allow edit of review
            ...prev_info,
            editable: true,
        }));
    }

    return(
        review_info.visible
        ?
        <div className="row review-entry mb-3">
            <div className="review-header row p-3">
                    <div className='col-3 col-sm-3 col-md-2 col-lg-1'>
                        <img className="user_avatar float-start rounded-circle" srcSet={props.author_avatar_urls['96']}></img>
                    </div>     
                    <div className='col'>
                        <h4>{props.author_name.charAt(0).toUpperCase() + props.author_name.slice(1) /* Capitalize the name of the Author */}</h4>
                        <time>{Utilities.format_date(props.date.slice(0, props.date.indexOf('T')) /* Convert the format of the date using the function in external library*/)}</time>
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
                        onClick={ () => is_loggedin ? update_response_in_database('none'): null }
                    />
                    : 
                    <FontAwesomeIcon
                        icon={faThumbsUp} 
                        size="xl" 
                        style={{ color: 'limegreen' }} 
                        onClick={ () => is_loggedin ? update_response_in_database('like'): null }
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
                        onClick={ () => is_loggedin ? update_response_in_database('none'): null }
                    />
                    :
                    <FontAwesomeIcon 
                        icon={faThumbsDown} 
                        size="xl" 
                        style={{ color: 'crimson' }} 
                        onClick={ () => is_loggedin ? update_response_in_database('dislike'): null }
                    />
                }
                <p>{review_info.dislike}</p>
                </div>
                {
                    is_loggedin
                    ?
                    <div className="float-end dropstart">
                        <a id="comment_user_actions" data-bs-toggle="dropdown" aria-expanded="false">
                            <FontAwesomeIcon 
                                icon={faEllipsis} 
                                size="xl" 
                                style={{ color: 'grey' }}
                            />
                        </a>
                        <ul className="dropdown-menu" aria-labelledby="comment_user_actions">
                            {
                            user_id == props.author 
                            ? 
                            <a className="dropdown-item" onClick={review_edit}>Edit</a>
                            : 
                            null
                            }
                            {user_id == props.author ? <a className="dropdown-item" onClick={delete_review}>Delete</a>: null}
                            <a className="dropdown-item" >Report</a>
                        </ul>
                    </div>
                    :
                    null
                }                 
            </div>
        </div>
        :
        null
    );
}