
import React from 'react';
import propTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';

import {
    faMagnifyingGlass,
}
    from '@fortawesome/free-solid-svg-icons';

/**
A component for searching.
@component
@param {object} props - The props object.
@param {string} props.value - A default value for the search
@param {function} props.updateSearch - A function that takes a search string and fetches the novels
@return {JSX.Element} A form element containing an input for searching novels.
*/
export default function Search({updateSearch, value}) {
    const [search, updateSearchState] =React.useState(value);

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
        <form id="search-form" onSubmit={handleSubmit}>
            <input type="search" id="search" name="search" value={search} onInput={handleInput} placeholder='Search Novel'>
            </input>
            <button id="search-button">
                <FontAwesomeIcon
                    icon={faMagnifyingGlass}
                    size="lg"
                    style={{color: 'white'}}
                />
            </button>
        </form>
    );
}

Search.propTypes = {
    value: propTypes.string,
    updateSearch: propTypes.func.isRequired,
};

Search.defaultProps ={
    value: '',
};
