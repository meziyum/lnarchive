
import React from 'react';
import propTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';

import {
    faMagnifyingGlass,
}
    from '@fortawesome/free-solid-svg-icons';

/**
A component for searching novels.
@component
@param {object} props - The props object.
@param {function} props.updateSearch - A function that takes a search string and fetches the novels
@return {JSX.Element} A form element containing an input for searching novels.
*/
export default function NovelSearch({updateSearch}) {
    const [search, updateSearchState] =React.useState('');

    const handleInput = (event) => {
        updateSearchState(event.target.value);
        if (event.target.value === '') {
            updateSearch(event, event.target.value);
        }
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        updateSearch(event, search);
    };

    return (
        <form id="novel-search-form" onSubmit={handleSubmit}>
            <input type="search" id="nsearch" name="nsearch" value={search} onInput={handleInput} placeholder='Search Novel'>
            </input>
            <button id="search-button">
                <FontAwesomeIcon
                    icon={faMagnifyingGlass}
                    size="l"
                    style={{color: 'white'}}
                />
            </button>
        </form>
    );
}

NovelSearch.propTypes = {
    updateSearch: propTypes.func.isRequired,
};
