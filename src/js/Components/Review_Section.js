
//Imports
import * as Utilities from '../utilities';
import React from 'react';
import Review from './Review.js';
import Pagination from './Pagination';

//Localised Constants from Server
const post_id = LNarchive_variables.object_id;
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const user_nonce = LNarchive_variables.nonce;
const user_id = LNarchive_variables.user_id;

export default function Review_Section( props ){ //Review Section React Component

    const [ section_info, update_section_info ] = React.useState({ //Section INformation States
        comment_list: [],
        comments_count: props.comments_count,
        pagination: null,
        pagination_display: false,
        current_page: 1,
        current_sort: 'likes',
        review_content: "",
    });

    let is_loggedin = props.is_loggedin; //Logged in status
    let comment_type = props.comment_type.charAt(0).toUpperCase() + props.comment_type.slice(1); //Comment type
    let comments_per_page = 10; //Number of comments to display per page

    React.useMemo( function(){ //useMemo Hook
        fetch_comments( section_info.current_sort, section_info.current_page);
    }, [ section_info.current_page, section_info.current_sort, section_info.comments_count]);

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

        if( res.status === 201 ){ //If successfully created comment
            update_section_info( prev_info => ({ //Update the section states
                ...prev_info,
                comments_count: ++prev_info.comments_count,
                current_sort: 'date',
                review_content: "",
            }));
        }
    }

    async function fetch_comments(){ //Function to fetch the comments

        let fields = "&_fields=id,author_name,author,author_avatar_urls,content,date,post,user_id,meta,is_logged_in,user_comment_response,rating"; //Comment fields to get

        const res = await fetch( `${wp_request_url}comments?post=${post_id}&orderby=${section_info.current_sort}&per_page=${comments_per_page}&page=${section_info.current_page}${fields}`, {
            headers: { //Actions on the HTTP Request
                'X-WP-Nonce' : user_nonce,
            },
        }) //Fetch the comments
        const data= await res.json(); //convert the data into json

        if( res.status === 200 ){ //If successful response
            const comments_map = data.map( comment => { //Map the fetched data into a comments list
                return (
                    <Review
                        key={comment.id} //Map Key
                        is_loggedin={is_loggedin}
                        user_id={user_id}
                        delete_review={delete_review}
                        {...comment} //Comment Data
                    />
            )});

            update_section_info( prev_info => ({ //Update the form data
                ...prev_info,
                comment_list: comments_map,
                pagination: <Pagination current_page={section_info.current_page} length={Math.ceil(section_info.comments_count/comments_per_page)} handleclick={handle_page_select}></Pagination>,
            }));
        }
        console.log(section_info.comment_list)
    }

    function handle_change( event ){ //Function to handle all changes in the form

        const {name, value, type} = event.target; //Destructure the values from the target element

        update_section_info( prev_info => ({ //Update the form states
            ...prev_info,
            [name]: value,
        }));
    }

    function handle_page_select( event ){ //Function to handle page select
        update_section_info( prev_info => ({
            ...prev_info,
            current_page: parseInt(event.target.value),
        }));
        document.getElementById("reviews-form").scrollIntoView(true); //Scroll to top of the comments list
    }

    function delete_review( id, event ) { //Delete Review function

        if( !window.confirm("Are you sure you want to delete your Review?") ) //If the user doesnt click OK
            return;

        fetch( `${wp_request_url}comments/${id}`, {
            method: "DELETE", //Method
            headers: { //Actions on the HTTP Request
                'X-WP-Nonce' : user_nonce,
            },
        }) //Delete a comment
        update_section_info( prev_info => ({
            ...prev_info,
            comments_count: --prev_info.comments_count,
        }))
    }
    
    return(
        <>
            <h2 className="d-flex justify-content-center review-title">{comment_type+"s"}</h2>
            {
                is_loggedin 
                ?
                <form id="reviews-form" className="mb-3" onSubmit={submit_review}>
                    <h4>Write a {comment_type}</h4>
                    <textarea name="review_content" id="review_content" onChange={handle_change} value={section_info.review_content}/>
                    <div className="d-flex justify-content-end"> 
                    <button className="px-3 py-2" id="review-submit">Submit</button>
                    </div>        
                </form>
                :
                <h3>You need to be <a href={props.login_url}>logged in</a> to submit a {comment_type}</h3>
            }
            {
                section_info.comments_count>0
                &&
                <div id="reviews-filter-header" className="d-flex justify-content-end">
                    <label htmlFor="review-filter" className="me-1">Sort by:</label>
                    <select name="current_sort" id="review-filter" onChange={handle_change} value={section_info.current_sort}>
                    <option value="likes">Popularity</option>
                    <option value="date">Newest</option>
                    </select>
                </div>
            }
            <div id="reviews-list" className="ps-0">
                {section_info.comment_list}
            </div>
            <div id="review-pagination" className="d-flex justify-content-center">
                {section_info.pagination}
            </div>
        </>
    );
}