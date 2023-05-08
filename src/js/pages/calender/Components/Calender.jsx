
import React from 'react';
import NovelItem from '../../../Components/NovelItem.jsx';
import {formatDate} from '../../../helpers/utilities.ts';
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
A React component that renders a calender with future novel releases information.
@param {Object} props - The component props.
@param {Array} props.formatsList - A list of formats available for the novel
@return {JSX.Element} - A Select component with the specified options and callbacks.
*/
export default function Calender(props) {
    const lastResponseLength = React.useRef(0);
    const [calenderStates, updateCalenderStates] = React.useState({
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
    }, [calenderStates.currentPage, calenderStates.selectedFormat, calenderStates.search]);

    const getVolumes = async () => {
        const fields =`id,title.rendered,novel_link,meta.${calenderStates.selectedFormat.value},_links.wp:featuredmedia`;

        const response = await fetch( `${wpRequestURL}volumes?_embed=wp:featuredmedia&_fields=${fields}&per_page=${volumePerPage}&page=${calenderStates.currentPage}&page=${calenderStates.currentPage}&per_page=${volumePerPage}&orderby=${calenderStates.selectedFormat.value}&search=${calenderStates.search}`, {
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
                <NovelItem key={volume.id} id={volume.id} link={volume.novel_link} novelCover={volumeCover} releaseDate={formatDate(volume.meta[calenderStates.selectedFormat.value][0])}/>
            );
        });
        lastResponseLength.current=volumes.length;

        updateCalenderStates( (prevInfo) => ({
            ...prevInfo,
            list: prevInfo.currentPage === 1 ? volumes : [...prevInfo.list, ...volumes],
            volumesFound: volumes.length>0 ? true : false,
        }));
    };

    const handleSelect = (data) => {
        updateCalenderStates( (prevInfo) => ({
            ...prevInfo,
            selectedFormat: data,
            currentPage: 1,
        }));
    };

    const updateSearch = (event, value) => {
        event.preventDefault();
        updateCalenderStates( (prevInfo) => ({
            ...prevInfo,
            search: value,
            currentPage: 1,
        }));
    };

    const handleInView = () => {
        if (lastResponseLength.current==volumePerPage) {
            updateCalenderStates( (prevInfo) => ({
                ...prevInfo,
                currentPage: ++prevInfo.currentPage,
            }));
        } else {
            updateCalenderStates( (prevInfo) => ({
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
                    value={calenderStates.selectedFormat}
                    isClearable={false}
                    onChange={(data) => handleSelect(data)}
                    styles={reactSelectStyle}
                />
            </div>
            <div className='calender-list row'>
                {calenderStates.list}
                {!calenderStates.volumesFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView} displayLoader={calenderStates.displayInfiniteLoader && calenderStates.volumesFound}/>
        </>
    );
}

Calender.propTypes = {
    formatsList: PropTypes.array.isRequired,
};
