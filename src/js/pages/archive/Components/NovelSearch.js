
import React from 'react';
import propTypes from 'prop-types';

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
            updateSearch(event.target.value);
        }
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        updateSearch(search);
    };

    return (
        <form onSubmit={handleSubmit}>
            <input type="search" id="nsearch" name="nsearch" value={search} onInput={handleInput}>
            </input>
        </form>
    );
}

NovelSearch.propTypes = {
    updateSearch: propTypes.func.isRequired,
};
