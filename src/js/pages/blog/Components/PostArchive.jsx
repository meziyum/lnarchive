
import React from 'react';
import PostItem from '../../../Components/PostItem';
import InfiniteScroll from '../../../extensions/InfiniteScroll';
import ResultsNotFound from '../../../Components/ResultsNotFound';
import {formatDate, formatTitle} from '../../../helpers/utilities';
import Search from '../../../Components/Search.jsx';
import TaxSelect from '../../../Components/TaxSelect';
import useToggle from '../../../hooks/useToggle';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import PropTypes from 'prop-types';

import {
    faSliders,
}
    from '@fortawesome/free-solid-svg-icons';

const urlParams = new URLSearchParams(window.location.search);
/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const postPerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
Renders a page displaying a list of novels with filtering and sorting functionality
@param {Object} props - Component props
@param {Array} props.filterData - An array of objects representing the available filters for the post archive
@return {JSX.Element} - Rendered PostArchive component
*/
function PostArchive(props) {
    const defaultApplitedFilters = () => {
        if (urlParams.entries().next().value !== undefined) {
            const filterName = urlParams.entries().next().value[0];
            const filterValue = urlParams.entries().next().value[1];
            const taxName = filterName.slice(0, -7);
            const tax = props.filterData.find( (tax) => tax.taxQueryName === taxName);

            if (tax) {
                const defaultValue = tax.list.find((option) => option.term_name === filterValue);
                toggleFilters();
                return {
                    [taxName]: [{value: defaultValue.term_id, label: defaultValue.label}],
                };
            }
        }
        return {};
    };

    const defaultSearchValue = () => {
        const searchValue = urlParams.get('searchFilter');

        if (searchValue) {
            return searchValue;
        } else {
            return '';
        }
    };

    const [showFilters, toggleFilters] = useToggle();
    const lastResponseLength = React.useRef(0);
    const [appliedFilters, setAppliedFilters] = React.useState(defaultApplitedFilters);
    const [archiveInfo, updateArchiveInfo] = React.useState({
        post_list: '',
        postsFound: true,
        displayInfiniteLoader: true,
        currentPage: 1,
        search: defaultSearchValue(),
        order: {value: 'desc', label: 'Descending'},
        order_by: {value: 'date', label: 'Post Date'},
    });

    React.useEffect( () => {
        getPosts();
    }, [archiveInfo.currentPage, archiveInfo.order_by, archiveInfo.order, archiveInfo.search, appliedFilters]);

    React.useEffect( () => {
        history.replaceState(null, null, window.location.pathname);
    }, []);

    const getPosts = async () => {
        try {
            let filters=``;
            Object.entries(appliedFilters).forEach((value) => {
                const [taxName, list] = value;
                if (list.length>0) {
                    let currentFilter= `&${taxName}=`;
                    list.forEach((term) => {
                        currentFilter+=`${term.value},`;
                    });
                    filters+=currentFilter.slice(0, -1);
                }
            });

            const fields = 'id,title.rendered,date,categoryList,link,categories,_links.wp:featuredmedia,_links.wp:term';

            const response = await fetch( `${wpRequestURL}posts?_embed&_fields=${fields}${filters}&per_page=${postPerPage}&page=${archiveInfo.currentPage}&order=${archiveInfo.order.value}&orderby=${archiveInfo.order_by.value}&search=${archiveInfo.search}`, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                }},
            );

            const data= await response.json();
            const posts = data.map( (post) => {
                const postImg=post._embedded['wp:featuredmedia'] ? post._embedded['wp:featuredmedia'][0].source_url : null;
                return (
                    <PostItem key={post.id} id={post.id} title={formatTitle(post.title.rendered)} date={formatDate(post.date)} link={post.link} postImage={postImg} categoryList={post.categoryList}/>
                );
            });
            lastResponseLength.current=posts.length;

            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                post_list: prevInfo.currentPage === 1 ? posts : [...prevInfo.post_list, posts],
                postsFound: posts.length>0 ? true : false,
            }));
        } catch (error) {
            lastResponseLength.current=0;
        }
    };

    const handleInView = () => {
        if (lastResponseLength.current==postPerPage) {
            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                currentPage: ++prevInfo.currentPage,
            }));
        } else {
            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                displayInfiniteLoader: false,
            }));
        }
    };

    const handleFilter = ( data, name ) => {
        setAppliedFilters( (prevInfo) => ({
            ...prevInfo,
            [name]: data,
        }));
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            currentPage: 1,
        }));
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
                <FontAwesomeIcon
                    title='Search'
                    icon={faSliders}
                    size="xl"
                    style={{color: showFilters ? '#387ef2' : 'grey'}}
                    onClick={toggleFilters}
                />
            </div>
            {showFilters &&
                <div id="archive-filter">
                    {props.filterData.map( (tax) =>{
                        return (
                            <TaxSelect key={`${tax.taxQueryName}_filter`} {...tax} handleFilter={handleFilter} selectValue={appliedFilters[tax.taxLabel]}/>
                        );
                    })}
                </div>
            }
            <div className="post-list row">
                {archiveInfo.postsFound && archiveInfo.post_list}
                {!archiveInfo.postsFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView} displayLoader={archiveInfo.displayInfiniteLoader && archiveInfo.postsFound}/>
        </>
    );
}

export default PostArchive;

PostArchive.propTypes = {
    filterData: PropTypes.array.isRequired,
};
