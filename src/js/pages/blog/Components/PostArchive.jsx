
import React from 'react';
import PostItem from '../../../Components/PostItem.jsx';
import InfiniteScroll from '../../../extensions/InfiniteScroll.js';
import ResultsNotFound from '../../../Components/ResultsNotFound.jsx';
import {formatDate} from '../../../helpers/utilities.js';
import Search from '../../../Components/Search.jsx';

const urlParams = new URLSearchParams(window.location.search);
/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url+'wp/v2/';
const postPerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
Renders a page displaying a list of novels with filtering and sorting functionality
@param {Object} props - Component props
@return {JSX.Element} - Rendered PostArchive component
*/
function PostArchive(props) {
    const defaultSearchValue = () => {
        const searchValue = urlParams.get('searchFilter');

        if (searchValue) {
            return searchValue;
        } else {
            return '';
        }
    };

    const lastResponseLength = React.useRef(0);
    const [archiveInfo, updateArchiveInfo] = React.useState({
        post_list: '',
        postsFound: true,
        currentPage: 1,
        search: defaultSearchValue(),
        order: {value: 'asc', label: 'Ascending'},
        order_by: {value: 'date', label: 'Post Date'},
    });

    React.useEffect( () => {
        getPosts();
    }, [archiveInfo.currentPage, archiveInfo.order_by, archiveInfo.order, archiveInfo.search]);

    const getPosts = async () => {
        const fields = 'id,title.rendered,modified,categoryList,link,categories,_links.wp:featuredmedia,_links.wp:term';

        const response = await fetch( `${wpRequestURL}posts?_embed&_fields=${fields}&per_page=${postPerPage}&page=${archiveInfo.currentPage}&order=${archiveInfo.order.value}&orderby=${archiveInfo.order_by.value}&search=${archiveInfo.search}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            }},
        );

        const data= await response.json();
        const posts = data.map( (post) => {
            return (
                <PostItem key={post.id} id={post.id} title={post.title.rendered} date={formatDate(post.modified)} link={post.link} postImage={post._embedded['wp:featuredmedia'][0].source_url} categoryList={post.categoryList}/>
            );
        });
        console.log(data)

        lastResponseLength.current=posts.length;

        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            post_list: prevInfo.currentPage === 1 ? posts : [...prevInfo.post_list, posts],
            postsFound: posts.length>0 ? true : false,
        }));
        history.replaceState(null, null, window.location.pathname);
    };

    const handleInView = () => {
        if (lastResponseLength.current==postPerPage) {
            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                currentPage: ++prevInfo.currentPage,
            }));
        }
    };

    const updateSearch = (event, value) => {
        event.preventDefault();
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            search: value,
            currentPage: 1,
        }));
    };

    return (
        <>
            <div id="post-wrap-header">
                <Search updateSearch={updateSearch} value={archiveInfo.search} label='Post'/>
            </div>
            <div className="post-list row">
                {archiveInfo.postsFound && archiveInfo.post_list}
                {!archiveInfo.postsFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView}/>
        </>
    );
}

export default PostArchive;
