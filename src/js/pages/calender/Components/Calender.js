
import React from 'react';
import NovelItem from '../../../Components/NovelItem.js';
import {formatDate} from '../../../helpers/utilities.js';
import Select from 'react-select';
import {reactSelectStyle} from '../../../helpers/reactSelectStyles.js';
import PropTypes from 'prop-types';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url+'wp/v2/';
const volumePerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
A React component that renders a calender with future novel releases information.
@param {Object} props - The component props.
@param {Array} props.formatsList - A list of formats available for the novel
@return {JSX.Element} - A Select component with the specified options and callbacks.
*/
export default function Calender(props) {
    const [calenderStates, updateCalenderStates] = React.useState({
        currentPage: 1,
        list: '',
        selectedFormat: {value: 'published_date_value_Kindle', label: 'Kindle'},
    });

    const options = props.formatsList.map( (format) => ({
        value: `published_date_value_${format}`,
        label: format,
    }));

    React.useEffect( () => {
        getVolumes();
    }, [calenderStates.currentPage, calenderStates.selectedFormat]);

    const getVolumes = async () => {
        const fields =`id,title.rendered,novel_link,meta.${calenderStates.selectedFormat.value},_links.wp:featuredmedia`;

        const response = await fetch( `${wpRequestURL}volumes?_embed=wp:featuredmedia&_fields=${fields}&page=${calenderStates.currentPage}&per_page=${volumePerPage}&orderby=${calenderStates.selectedFormat.value}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        const data= await response.json();
        console.log(data);

        const volumes = data.map( (volume) => {
            return (
                <NovelItem key={volume.id} id={volume.id} link={volume.novel_link} novelCover={volume._embedded['wp:featuredmedia'][0].source_url} releaseDate={formatDate(volume.meta[calenderStates.selectedFormat.value][0])}/>
            );
        });

        updateCalenderStates( (prevInfo) => ({
            ...prevInfo,
            list: volumes,
        }));
    };

    const handleSelect = (data) => {
        updateCalenderStates( (prevInfo) => ({
            ...prevInfo,
            selectedFormat: data,
        }));
    };

    return (
        <>
            <Select
                options={options}
                value={calenderStates.selectedFormat}
                isClearable={false}
                onChange={ (data) => handleSelect(data)}
                styles={reactSelectStyle}
            />
            <div className='calender-list row'>
                {calenderStates.list}
            </div>
        </>
    );
}

Calender.propTypes = {
    formatsList: PropTypes.array.isRequired,
};
