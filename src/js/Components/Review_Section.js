
//Imports
import * as Utilities from '../utilities';
import React, { Fragment } from 'react';
import Review from './Review.js';

//Localised Constants from Server
const post_id = LNarchive_variables.object_id;
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const user_nonce = LNarchive_variables.nonce;
const user_id = LNarchive_variables.user_id;

export default function Review_Section( props ){ //Review Section React Component

    const [ section_info, update_section_info ] = React.useState({
        comment_list: [],
        pagination: [],
        pagination_display: false,
        current_page: 1,
        current_sort: 'likes',
        review_content: "",
    });

    let is_loggedin = props.is_loggedin; //Logged in status
    let comment_type = props.comment_type.charAt(0).toUpperCase() + props.comment_type.slice(1); //Comment type
    let comments_total_count = 11; //Get total count of comments for the post
    let comments_per_page = 10; //Number of comments to display per page

    React.useEffect( function(){ //Initial effect on first render
        fetch_comments( 'likes', section_info.current_page);
        comment_pagination();
    }, [ section_info.current_page, section_info.current_sort]);

    async function submit_review( event ){ //Submit Review Button onclick function

        event.preventDefault(); //Prevent reload of the page on submit

        if( section_info.review_content == '') //Prevent Submission when no content has been entered
            return;

        const res = await fetch( `${wp_request_url}comments`, { //Fetch the comments
            method: "POST", //Method
            credentials: 'same-origin', //Send Credentials
            headers: { //Actions on the HTTP Request
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
            body: JSON.stringify({ //Data to attach to the HTTP Request
                content: Utilities.esc_html(section_info.review_content), //Review Content
                post: post_id, //Post Id
                meta: {"likes": 0, "dislikes": 0}, //Default meta values
            })
        }) //Submit a comment
        const data = await res.json(); //Conver the data into json

        update_section_info( prev_info => ({ //Update the section states
            ...prev_info,
            comment_list: [ <Review
                key={data.id} //Map Key
                is_loggedin={is_loggedin}
                    user_id={user_id}
                {...data} //Comment Data
                />, //New Review Element
                ...prev_info.comment_list,          
            ],
            review_content: "",
        }));
    }

    async function fetch_comments(){

        const res = await fetch( `${wp_request_url}comments?post=${post_id}&orderby=${section_info.current_sort}&per_page=${comments_per_page}&page=${section_info.current_page}`, {
            headers: { //Actions on the HTTP Request
                'X-WP-Nonce' : user_nonce,
            },
        }) //Fetch the comments
        const data= await res.json(); //convert the data into json
        const comments_map = data.map( comment => { //Map the fetched data into a comments list
            return (
                    <Review
                        key={comment.id} //Map Key
                        is_loggedin={is_loggedin}
                        user_id={user_id}
                        {...comment} //Comment Data
                    />
            );
        });

        update_section_info( prev_info => ({ //Update the form data
            ...prev_info,
            comment_list: comments_map,
        }));
    }

    function handle_change( event ){ //Function to handle all changes in the form

        const {name, value, type} = event.target; //Destructure the values from the target element

        update_section_info( prev_info => ({ //Update the form states
            ...prev_info,
            [name]: value,
        }));
    }

    function comment_pagination(){

        var pagination=[];
        var current_page = section_info.current_page;
        var length = Math.ceil(comments_total_count/comments_per_page);

        var start = current_page-2 > 1 ? current_page-2: 1;
        var end = current_page+2 > length ? length : current_page+2;

        while( start<=end){
            pagination.push(
                <button key={start} value={start} onClick={handle_page_select} className={ start==current_page ? "current" : undefined}>{start}</button>
            );
            start++;
        }

        if( current_page-2>1 )
            pagination=[
                <button key='1' value='1' onClick={handle_page_select}>{'<<'}</button>,
                <button key='...'>{'...'}</button>,
                ...pagination,
            ];
        
        if( current_page+2<length )
            pagination=[
                ...pagination,
                <button key='....'>{'...'}</button>,
                <button key={length} value={length} onClick={handle_page_select}>{'>>'}</button>,
            ];

        update_section_info( prev_info => ({
            ...prev_info,
            pagination: [pagination],
            pagination_display: length!=1 ? true : false,
        }));
    }

    function handle_page_select( event ){
        update_section_info( prev_info => ({
            ...prev_info,
            current_page: parseInt(event.target.value),
        }));
        document.getElementById("reviews-list").scrollIntoView();
    }

    return(
        <>
            <h2 className="d-flex justify-content-center review-title">{comment_type+"s"}</h2>
            <h4>Write a {comment_type}</h4>
            <form id="reviews-form" className="mb-3" onSubmit={submit_review}>
                <textarea name="review_content" id="review_content" onChange={handle_change} value={section_info.review_content}/>
                <div className="d-flex justify-content-end"> 
                <button className="px-3 py-2" id="review-submit">Submit</button>
                </div>        
            </form>
            <div id="reviews-filter-header" className="d-flex justify-content-end">
                <label htmlFor="review-filter" className="me-1">Sort by:</label>
                <select name="current_sort" id="review-filter" onChange={handle_change}>
                <option value="likes">Popularity</option>
                <option value="date">Newest</option>
            </select>
            </div>
            <div id="reviews-list" className="ps-0">
                {section_info.comment_list}
            </div>
            <div id="review-pagination" className="d-flex justify-content-center">
                <div className="page-list">
                    {section_info.pagination_display && section_info.pagination}
                </div>
            </div>
        </>
    );
}