
import React from 'react';
import Ratings from '../../../Components/Ratings.js';

const post_id = LNarchive_variables.object_id;
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;

export default function Novel_Actions( props ){

    const [action_states, update_action_states] = React.useState({
        rating: props.rating,
    });

    function submit_ratings( value){
        fetch( `${custom_api_request_url}submit_rating/${post_id}`, {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
            body: JSON.stringify({
                rating: value,
            })
        })
        update_action_states( prev_states => ({
            ...prev_states,
            rating: value,
        }));
    }

    return(
        <>{props.is_loggedin && <Ratings ratings_submit={submit_ratings} size={'xl'} rating={action_states.rating} mode={'form'}/>}</>
    )
}