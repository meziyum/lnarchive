
import React from 'react';
import Select from 'react-select';
import {reactSelectStyle} from '../../../helpers/reactSelectStyles.js';

const params = new URLSearchParams(window.location.search);

export default function Fillter_Select({tax_query_name, tax_label, list, handleFilter, selectValue}) {

    const options = list.map(term => ({
        value: term.term_id,
        label: term.term_name
    }));
    const query = params.get(`${tax_query_name}_filter`);
    const defaultValue = options.find(option => option.label === query);

    return(
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
