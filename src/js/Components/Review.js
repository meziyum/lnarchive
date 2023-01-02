
//Import Libraries
import * as Main from '../main.js';
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
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;

export default function Review( props ){ //Review Entry React Component

    const [ likes_count, update_likes] = React.useState(props.meta.likes); //Define likes count state
    const [ dislikes_count, update_dislikes] = React.useState(props.meta.dislikes); //Define dislike count state
    const [ user_response, update_response] = props.user_comment_response.length != 0? React.useState(props.user_comment_response[0].response_type): React.useState('none'); //Define user response state
    const [ review_expanded, update_review_expanded] = React.useState( false ); //Define Review expanded or collapsed status
    const [ review_editable, update_review_editable] = React.useState( false ); //Define review editable status

    //Local Component Variables
    var user_id =props.user_id;
    var is_loggedin=props.is_loggedin;
    var read_more_length = 700;
    var comment_content = props.content.rendered;

    function update_response_in_database( action ){ //Function to update the user response
        fetch( custom_api_request_url+'comment/'+action+'/'+props.id, {
            method: "POST", //Method
            credentials: 'same-origin', //Send Credentials
            headers: { //Actions on the HTTP Request
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
        }) //Fetch the comments

        if( user_response == 'like' ){
            if( action == 'dislike'){ //Change user response from dislike to like
                update_likes( old_likes => --old_likes);
                update_dislikes( old_dislikes => ++old_dislikes);
            }
            else if(action == 'none') //like
                update_likes( old_likes => --old_likes);        
        }
        else if( user_response == 'dislike' ) {
            if( action == 'like'){ //Change user response from like to dislike
                update_dislikes( old_dislikes => --old_dislikes);
                update_likes( old_likes => ++old_likes);
            }
            else if(action == 'none') //dislike
                update_dislikes( old_dislikes => --old_dislikes);
        }
        else{
            if( action == 'like'){ //Remove like response
                update_likes( old_likes => ++old_likes);
            }
            else if( action == 'dislike') //Remove dislike response
                update_dislikes( old_dislikes => ++old_dislikes);
        }
        update_response( () => action ); //update the response state
    }

    function delete_review() {

        var confirmation = window.confirm("Are you sure you want to delete your Review?");

        if( confirmation ){
            console.log('deleted');
        }
    }

    function update_edit(){
        update_review_editable( true );
        console.log(review_editable);
    }

    return(
        <div className="row review-entry">
            <div className="review-header row">
                    <div className='col-3 col-sm-3 col-md-2 col-lg-1'>
                        <img className="user_avatar float-start" srcSet={props.author_avatar_urls['96']}></img>
                    </div>     
                    <div className='col'>
                        <h4>{props.author_name.charAt(0).toUpperCase() + props.author_name.slice(1) /* Capitalize the name of the Author */}</h4>
                        <time>{Main.format_date(props.date.slice(0, props.date.indexOf('T')) /* Convert the format of the date using the function in external library*/)}</time>
                    </div>     
            </div>

                <div className="review-content" contentEditable={review_editable} dangerouslySetInnerHTML={ {__html:  (review_expanded || comment_content.length<=read_more_length) ?  comment_content : comment_content.substring(0, comment_content.substring(0,read_more_length).lastIndexOf(" "))+"..."}}/>
            <div className="d-flex justify-content-center">
                {
                    comment_content.length > read_more_length
                    ?
                    <a onClick={ () => update_review_expanded( old_value =>  !old_value)}>
                        <FontAwesomeIcon 
                            icon={ review_expanded ? faChevronUp : faChevronDown}
                            size="lg"
                        />
                        Read more
                    </a>
                    :
                    null
                }
            </div>
            <div className="review-footer">
                <div className='float-start d-flex'>
                { 
                    user_response == 'like' 
                    ? 
                    <FontAwesomeIcon 
                        icon={faThumbsUpSolid} 
                        size="xl" 
                        style={{ color: 'limegreen' }}
                        onClick={ () => is_loggedin ? update_response_in_database('none'): null }
                    />
                    : <FontAwesomeIcon 
                        icon={faThumbsUp} 
                        size="xl" 
                        style={{ color: 'limegreen' }} 
                        onClick={ () => is_loggedin ? update_response_in_database('like'): null }
                    />
                }
                <p>{likes_count}</p>
                { 
                    user_response == 'dislike' 
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
                <p>{dislikes_count}</p>
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
                            {user_id == props.author ? <a className="dropdown-item" onClick={ update_edit}>Edit</a> : null}
                            {user_id == props.author ? <a className="dropdown-item" onClick={delete_review}>Delete</a>: null}
                            <a className="dropdown-item" >Report</a>
                        </ul>
                    </div>
                    :
                    null
                }                 
            </div>
        </div>
    );
}