
import React from 'react';
import NovelItem from '../../../Components/NovelItem.jsx';
import {formatDate, formatTitle} from '../../../helpers/utilities.ts';
import Select from 'react-select';
import {reactSelectStyle} from '../../../helpers/reactSelectStyles.js';
import PropTypes from 'prop-types';
import Search from '../../../Components/Search.jsx';
import ResultsNotFound from '../../../Components/ResultsNotFound.jsx';
import InfiniteScroll from '../../../extensions/InfiniteScroll.js';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const volumePerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
A React component that renders a calendar with future novel releases information.
@param {Object} props - The component props.
@param {Array} props.formatsList - A list of formats available for the novel
@return {JSX.Element} - A Select component with the specified options and callbacks.
*/
export default function Calendar(props) {
    const lastResponseLength = React.useRef(0);
    const [calendarStates, updatecalendarStates] = React.useState({
        currentPage: 1,
        list: '',
        displayInfiniteLoader: true,
        selectedFormat: {value: 'published_date_value_Kindle', label: 'Kindle'},
        search: '',
        volumesFound: true,
    });

    const options = props.formatsList.map( (format) => ({
        value: `published_date_value_${format}`,
        label: format,
    }));

    React.useEffect( () => {
        getVolumes();
    }, [calendarStates.currentPage, calendarStates.selectedFormat, calendarStates.search]);

    const getVolumes = async () => {
        const fields =`id,title.rendered,novel_link,meta.${calendarStates.selectedFormat.value},_links.wp:featuredmedia`;

        const response = await fetch( `${wpRequestURL}volumes?_embed=wp:featuredmedia&_fields=${fields}&per_page=${volumePerPage}&page=${calendarStates.currentPage}&page=${calendarStates.currentPage}&per_page=${volumePerPage}&orderby=${calendarStates.selectedFormat.value}&search=${calendarStates.search}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        const data= await response.json();

        const volumes = data.map( (volume) => {
            const volumeCover= volume._embedded ? volume._embedded['wp:featuredmedia'][0].source_url: null;
            return (
                <NovelItem key={volume.id} id={volume.id} link={`${volume.novel_link}?volumeFilter=${volume.id}&formatFilter=${calendarStates.selectedFormat.value.slice(21)}`} novelCover={volumeCover} releaseDate={formatDate(volume.meta[calendarStates.selectedFormat.value][0])} title={formatTitle(volume.title.rendered)}/>
            );
        });
        lastResponseLength.current=volumes.length;

        updatecalendarStates( (prevInfo) => ({
            ...prevInfo,
            list: prevInfo.currentPage === 1 ? volumes : [...prevInfo.list, ...volumes],
            volumesFound: volumes.length>0 ? true : false,
        }));
    };

    const handleSelect = (data) => {
        updatecalendarStates( (prevInfo) => ({
            ...prevInfo,
            selectedFormat: data,
            currentPage: 1,
        }));
    };

    const updateSearch = (event, value) => {
        event.preventDefault();
        updatecalendarStates( (prevInfo) => ({
            ...prevInfo,
            search: value,
            currentPage: 1,
        }));
    };

    const handleInView = () => {
        if (lastResponseLength.current==volumePerPage) {
            updatecalendarStates( (prevInfo) => ({
                ...prevInfo,
                currentPage: ++prevInfo.currentPage,
            }));
        } else {
            updatecalendarStates( (prevInfo) => ({
                ...prevInfo,
                displayInfiniteLoader: false,
            }));
        }
    };

    return (
        <>
            <div id="upcoming-releases-header">
                <Search updateSearch={updateSearch}/>
                <Select
                    options={options}
                    value={calendarStates.selectedFormat}
                    isClearable={false}
                    onChange={(data) => handleSelect(data)}
                    styles={reactSelectStyle}
                />
            </div>
            <div className='calendar-list row'>
                {calendarStates.list}
                {!calendarStates.volumesFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView} displayLoader={calendarStates.displayInfiniteLoader && calendarStates.volumesFound}/>
        </>
    );
}

Calendar.propTypes = {
    formatsList: PropTypes.array.isRequired,
};
