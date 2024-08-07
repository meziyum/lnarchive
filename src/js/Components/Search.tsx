
import React from 'react';
import propTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';

import {
    faMagnifyingGlass,
}
    from '@fortawesome/free-solid-svg-icons';

interface SearchPropType{
    updateSearch: Function,
    value?: string,
    label?: string,
}

/**
A component for searching.
@component
@param {object} props - The props object.
@param {string} props.value - A default value for the search
@param {function} props.updateSearch - A function that takes a search string and fetches the novels
@param {string} props.label - Label for the search
@return {JSX.Element} A form element containing an input for searching novels.
*/
export default function Search({updateSearch, value='', label='Novel'}: SearchPropType): JSX.Element {
    const [search, updateSearchState] =React.useState(value);

    const handleInput = (event: React.ChangeEvent<HTMLInputElement>) => {
        updateSearchState(event.target.value);
        if (event.target.value === '') {
            updateSearch(event, event.target.value);
        }
    };

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        updateSearch(event, search);
    };

    return (
        <form id="search-form" onSubmit={handleSubmit}>
            <input type="search" id="search" name="search" value={search} onInput={handleInput} placeholder={`Search ${label}`}>
            </input>
            <button id="search-button">
                <FontAwesomeIcon
                    title='Search'
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
    label: propTypes.string,
};