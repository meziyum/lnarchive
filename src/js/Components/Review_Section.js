
import * as Utilities from '../utilities.js';
import React from 'react';
import Review from './Review.js';
import Pagination from './Pagination.js';

const post_id = LNarchive_variables.object_id;
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.custom_api_url;
const user_nonce = LNarchive_variables.nonce;

export default function Review_Section( props ){

    const [ section_info, update_section_info ] = React.useState({
        comment_list: [],
        comments_count: props.comments_count,
        pagination: null,
        pagination_display: false,
        current_page: 1,
        current_sort: 'likes',
        review_content: "",
        progress: 0,
    });

    let is_loggedin = props.is_loggedin;
    let user_id = props.user_id;
    let comment_type = props.comment_type.charAt(0).toUpperCase() + props.comment_type.slice(1);
    let comments_per_page = 10;

    React.useMemo( function(){
        fetch_comments( section_info.current_sort, section_info.current_page);
    }, [ section_info.current_page, section_info.current_sort, section_info.comments_count]);

    async function submit_review( event ){

        event.preventDefault();

        if( section_info.review_content == '')
            return;

        const res = await fetch( `${custom_api_request_url}submit_comment`, {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce' : user_nonce,
            },
            body: JSON.stringify({
                content: Utilities.esc_html(section_info.review_content),
                post_id: post_id,
                progress: section_info.progress,
            })
        })

        if( res.status === 201 ){
            update_section_info( prev_info => ({
                ...prev_info,
                comments_count: ++prev_info.comments_count,
                current_sort: 'date',
                review_content: "",
            }));
        }
    }

    async function fetch_comments(){

        let fields = "&_fields=id,author_name,author,author_avatar_urls,content,date,post,user_id,meta,is_logged_in,user_comment_response,rating";

        const res = await fetch( `${wp_request_url}comments?post=${post_id}&orderby=${section_info.current_sort}&per_page=${comments_per_page}&page=${section_info.current_page}${fields}`, {
            headers: {
                'X-WP-Nonce' : user_nonce,
            },
        })
        const data= await res.json();

        if( res.status === 200 ){
            const comments_map = data.map( comment => {
                return (
                    <Review
                        key={comment.id}
                        is_loggedin={is_loggedin}
                        user_id={user_id}
                        delete_review={delete_review}
                        max_progress={props.max_progress}
                        {...comment}
                    />
            )});

            update_section_info( prev_info => ({
                ...prev_info,
                comment_list: comments_map,
                pagination: <Pagination current_page={section_info.current_page} length={Math.ceil(section_info.comments_count/comments_per_page)} handleclick={handle_page_select}></Pagination>,
            }));
        }
    }

    function handle_change( event ){

        const {name, value, type} = event.target;

        update_section_info( prev_info => ({
            ...prev_info,
            [name]: value,
        }));
    }

    function handle_page_select( event ){
        update_section_info( prev_info => ({
            ...prev_info,
            current_page: parseInt(event.target.value),
        }));
        document.getElementById("reviews-form").scrollIntoView(true);
    }

    async function delete_review( id) {

        if( !window.confirm("Are you sure you want to delete your Review?") )
            return;

        await fetch( `${wp_request_url}comments/${id}`, {
            method: "DELETE",
            headers: {
                'X-WP-Nonce' : user_nonce,
            },
        })

        update_section_info( prev_info => ({
            ...prev_info,
            comments_count: prev_info.comments_count-1,
        }));
    }
    
    return(
        <>
            <h2 className="d-flex justify-content-center review-title">{comment_type+"s"}</h2>
            {
                is_loggedin 
                ?
                <form id="reviews-form" className="mb-3" onSubmit={submit_review}>
                    {
                        comment_type == 'Review' && props.max_progress>0 &&
                        <div className="float-end"> 
                            <label htmlFor="progress"><h5>No of Volumes(Read): </h5></label>
                            <input type="number" id="progress" name="progress" value={section_info.progress} onChange={handle_change} min="0" max={props.max_progress}/>
                        </div>
                    }
                    <h4 className="float-start">Write your {comment_type}</h4>
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
                    <label htmlFor="review-filter" className="me-1">Sort:</label>
                    <select name="current_sort" id="review-filter" onChange={handle_change} value={section_info.current_sort}>
                    { is_loggedin && <option value="author">Your {comment_type}s</option>}
                    <option value="likes">Popularity</option>
                    <option value="date">Newest</option>
                    { props.max_progress >0 && <option value="progress">Progress</option>}
                    </select>
                </div>
            }
            <div id="reviews-list" className="ps-0">
                {section_info.comment_list}
                {section_info.pagination}
            </div>
        </>
    );
}

Review_Section.defaultProps ={
    is_loggedin: false,
    comment_type: 'comment',
    comments_count: 0,
    max_progress: 0,
}