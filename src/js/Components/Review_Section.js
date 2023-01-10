
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
        pagination: comment_pagination(5),
        current_page: 1,
        review_content: "",
    });

    const [ comment_list, update_comments_list ] = React.useState( props.comment_data ); //State of the Comments List
    let is_loggedin = props.is_loggedin; //Logged in status
    let comment_type = props.comment_type.charAt(0).toUpperCase() + props.comment_type.slice(1); //Comment type
    let comments_total_count = 111; //Get total count of comments for the post
    let comments_per_page = 10; //Number of comments to display per page

    function comment_pagination( current_page ){

        var pagination=[];
        var length = Math.ceil(comments_total_count/comments_per_page);

        for( let i=1; i<= length; i++){

            if( i<=current_page+2 && i>=current_page-2){
                pagination.push(
                    <button key={i} onClick={handle_page_select}>{i}</button>
                );
            }
        }

        return pagination;
    }

    function handle_page_select(){
        console.log('hmn')
    }

    function submit_review( event ){ //Submit Review Button onclick function

        event.preventDefault();

        if( section_info.review_content == '') //Prevent Submission when no content has been entered
            return;

        fetch( wp_request_url+"comments", { //Fetch the comments
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
        }) //Fetch the comments
        .then( res => res.json()) //Convert the data from Promise to JSON
        .then( data => { //Execute function after data is fetched
            update_section_info( prev_info => ({ //Update the form data
                ...prev_info,
                review_content: "",
            }));
            update_comments_list( prev_comments_list => { //Update State of the comment list
                return [ <Review
                    key={data.id} //Map Key
                    is_loggedin={is_loggedin}
                        user_id={user_id}
                    {...data} //Comment Data
                    />, //New Review Element
                    ...prev_comments_list //Previous Review elements stored in the array
                    ]
            });
        });
    }

    function apply_sort( orderby, page_no ){
        fetch( wp_request_url+"comments?post="+post_id+"&orderby="+orderby+"&per_page="+comments_per_page+"&page="+page_no, {
            headers: { //Actions on the HTTP Request
                'X-WP-Nonce' : user_nonce,
            },
        }) //Fetch the comments
        .then( res => res.json()) //Convert the data from Promise to JSON
        .then( data => { //Execut function after data is fetched
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
            update_comments_list( () => comments_map ); //Update Comment List State
        })
    }

    function handle_change( event ){ //Function to handle all changes in the form

        const {name, value, type} = event.target; //Destructure the values from the target element

        update_section_info( prev_info => ({ //Update the form states
            ...prev_info,
            [name]: value,
        }));
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
                <select name="review-filter" id="review-filter" onChange={() => apply_sort( document.getElementById('review-filter').value, 1 )}>
                <option value="likes">Popularity</option>
                <option value="date">Newest</option>
            </select>
            </div>
            <div id="reviews-list" className="ps-0">
                {comment_list}
            </div>
            <div id="review-pagination">
                {section_info.pagination}
            </div>
        </>
    );
}