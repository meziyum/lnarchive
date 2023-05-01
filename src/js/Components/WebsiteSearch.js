
import React from 'react';
import Select from 'react-select';
import {reactSelectStyle} from '../helpers/reactSelectStyles.js';

/**
A search component that allows users to select a search type and input a search query.
@param {Object} props - The props object containing any additional properties.
@return {JSX.Element} - A JSX Element representing the search component.
*/
export default function WebsiteSearch(props) {
    const [searchValue, updateSearchValue] =React.useState('');

    const handleSelect = (data) => {
        updateSearchValue(data);
        console.log(data)
    };

    return (
        <form id="main-search-form">
            <input id="main-search-input"/>
            <Select
                options={[
                    {value: 'novel', label: 'Novel'},
                    {value: 'post', label: 'Post'},
                ]}
                defaultValue={{value: 'post', label: 'Post'}}
                value={searchValue}
                onChange={(data) => handleSelect(data)}
                styles={reactSelectStyle}
            />
        </form>
    );
}