
import React from 'react';

/* eslint-disable no-undef */
const websiteURL = lnarchiveVariables.websiteURL;
const blogURL = lnarchiveVariables.blogURL;
/* eslint-enable no-undef */

/**
A search component that allows users to select a search type and input a search query.
@param {Object} props - The props object containing any additional properties.
@return {JSX.Element} - A JSX Element representing the search component.
*/
export default function WebsiteSearch(props) {
    const [searchInfo, updateSearchInfo] =React.useState({
        searchType: 'novel',
        searchContent: '',
    });

    const handleChange = (event) => {
        updateSearchInfo( (prevInfo) => ({
            ...prevInfo,
            [event.target.name]: event.target.value,
        }));
    };

    const search = (event) => {
        event.preventDefault();
        if (searchInfo.searchType == 'post') {
            window.location.href = blogURL;
        } else {
            window.location.href = `${websiteURL}/${searchInfo.searchType}/?searchFilter=${searchInfo.searchContent}`;
        }
    };

    return (
        <form id="main-search-form" onSubmit={search}>
            <input id="main-search-input" name="searchContent" placeholder='Search' onChange={(handleChange)}/>
            <select id="type-select" name="searchType" onChange={handleChange} defaultValue={'novel'}>
                <option value="novel">Novel</option>
                <option value="post">Post</option>
            </select>
        </form>
    );
}
