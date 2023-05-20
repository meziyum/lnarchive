
import React from 'react';
import PropTypes from 'prop-types';
import Select from 'react-select';
import {reactSelectStyle} from '../helpers/reactSelectStyles.js';

const params = new URLSearchParams(window.location.search);

/**
A React component that renders a Select input field for filtering items based on taxonomy terms.
@param {Object} props - The component props.
@param {string} props.taxQueryName - The name of the taxonomy being filtered.
@param {string} props.taxLabel - The label for the taxonomy being filtered.
@param {Array} props.list - An array of objects representing the taxonomy terms to be displayed in the select options.
@param {function} props.handleFilter - A callback function that handles the filtering logic when a term is selected.
@param {Array} props.selectValue - An array of objects representing the currently selected terms.
@return {JSX.Element} - A Select component with the specified options and callbacks.
*/
export default function TaxSelect({taxQueryName, taxLabel, list, handleFilter, selectValue}) {
    const options = list.map((term) => ({
        value: term.term_id,
        label: term.term_name,
    }));
    const query = params.get(`${taxQueryName}_filter`);
    const defaultValue = options.find((option) => option.label === query);

    return (
        <div>
            <h6>{taxLabel}</h6>
            <Select
                placeholder={`Select ${taxLabel}`}
                options={options}
                defaultValue={defaultValue}
                isMulti
                value={selectValue}
                onChange={ (data) => handleFilter(data, taxQueryName)}
                isClearable={true}
                styles={reactSelectStyle}
            />
        </div>
    );
}

TaxSelect.propTypes = {
    taxQueryName: PropTypes.string.isRequired,
    taxLabel: PropTypes.string.isRequired,
    list: PropTypes.arrayOf(
        PropTypes.shape({
            term_id: PropTypes.number.isRequired,
            term_name: PropTypes.string.isRequired,
        }).isRequired,
    ).isRequired,
    handleFilter: PropTypes.func.isRequired,
    selectValue: PropTypes.arrayOf(
        PropTypes.shape({
            value: PropTypes.number.isRequired,
            label: PropTypes.string.isRequired,
        }).isRequired,
    ),
};
