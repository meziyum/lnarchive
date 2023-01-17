
//Imports
import React from 'react';
import Ratings from './Ratings';

//Constant Variables from server side
const post_id = LNarchive_variables.object_id;
const custom_api_request_url = LNarchive_variables.wp_rest_url+'lnarchive/v1/';
const user_nonce = LNarchive_variables.nonce;

export default function Novel_Actions( props ){

    const [action_states, update_action_states] = React.useState({ //Novel Actions States
        rating: props.rating,
    });

    function submit_ratings( value){ //Function to submit ratings
        fetch( `${custom_api_request_url}submit_rating/${post_id}`, {
            method: "POST", //Method
            credentials: 'same-origin', //Send Credentials
            headers: { //Actions on the HTTP Request
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
            body: JSON.stringify({ //Data to attach to the HTTP Request
                rating: value,
            })
        }) //Submit Rating API REquest
        update_action_states( prev_states => ({ //Update the value of new rating submission
            ...prev_states,
            rating: value,
        }));
    }

    return(
        <>{props.is_loggedin && <Ratings ratings_submit={submit_ratings} size={'xl'} rating={action_states.rating} mode={'form'}/>}</>
    )
}