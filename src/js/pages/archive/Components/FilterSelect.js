
import React from 'react';
import Select from 'react-select';
import {reactSelectStyle} from '../../../helpers/reactSelectStyles.js';

const params = new URLSearchParams(window.location.search);

/**
A React component that renders a Select input field for filtering items based on taxonomy terms.
@param {Object} props - The component props.
@param {string} props.tax_query_name - The name of the taxonomy being filtered.
@param {string} props.tax_label - The label for the taxonomy being filtered.
@param {Array} props.list - An array of objects representing the taxonomy terms to be displayed in the select options.
@param {function} props.handleFilter - A callback function that handles the filtering logic when a term is selected.
@param {Array} props.selectValue - An array of objects representing the currently selected terms.
@return {JSX.Element} - A Select component with the specified options and callbacks.
*/
export default function FilterSelect({tax_query_name, tax_label, list, handleFilter, selectValue}) {
    const options = list.map(term => ({
        value: term.term_id,
        label: term.term_name,
    }));
    const query = params.get(`${tax_query_name}_filter`);
    const defaultValue = options.find((option) => option.label === query);

    return (
        <div>
            <h6>{tax_label}</h6>
            <Select
                placeholder={`Select ${tax_label}`}
                options={options}
                defaultValue={defaultValue}
                isMulti
                value={selectValue}
                onChange={ (data) => handleFilter(data, tax_query_name)}
                isClearable={true}
                styles={reactSelectStyle}
            />
        </div>
    );
}
