
import React from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faLeftLong, faRightLong} from '@fortawesome/free-solid-svg-icons';
import NovelItem from '../../../Components/NovelItem';
import {formatDate, formatTitle, getCurrentMonth, getCurrentYear, getCurrentMonthNameByNo} from '../../../helpers/utilities';
import Select from 'react-select';
import {reactSelectStyle} from '../../../style/reactSelectStyles';
import PropTypes from 'prop-types';
import ReactSelectData from '../../../types/ReactSelectData';
import Search from '../../../Components/Search';
import ResultsNotFound from '../../../layouts/ResultsNotFound';
import InfiniteScroll from '../../../extensions/InfiniteScroll';
import COLORS from '../../../style/colors';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const volumePerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

interface CalenderProps {
    formatsList: Array<string>;
}

interface CalendarStates {
    currentPage: number;
    list: Array<React.JSX.Element>;
    displayInfiniteLoader: boolean;
    selectedFormat: ReactSelectData;
    search: string;
    volumesFound: boolean;
    currentMonth: number;
    currentYear: number;
}

interface Volume {
    id: number;
    link: string;
    novel_link: string,
    title: {
        rendered: string;
    }
    _embedded: {
        'wp:featuredmedia': Array<{
            source_url: string;
        }>;
    };
    meta: object;
}

/**
A React component that renders a calendar with future novel releases information.
@param {Object} props - The component props.
@param {Array} props.formatsList - A list of formats available for the novel
@return {JSX.Element} - A Select component with the specified options and callbacks.
*/
const Calendar: React.FC<CalenderProps> = (props: CalenderProps) => {
    const lastResponseLength = React.useRef(0);
    const [calendarStates, updatecalendarStates] = React.useState<CalendarStates>({
        currentPage: 1,
        list: [],
        displayInfiniteLoader: true,
        selectedFormat: {value: 'published_date_value_Kindle', label: 'Kindle'},
        search: '',
        volumesFound: true,
        currentMonth: getCurrentMonth(),
        currentYear: getCurrentYear(),
    });

    const options = props.formatsList.map( (format) => ({
        value: `published_date_value_${format}`,
        label: format,
    }));

    React.useEffect( () => {
        getVolumes();
    }, [calendarStates.currentPage, calendarStates.selectedFormat, calendarStates.search, calendarStates.currentMonth]);

    const getVolumes = async () => {
        const fields =`id,title.rendered,novel_link,meta.${calendarStates.selectedFormat.value},_links.wp:featuredmedia`;

        const response = await fetch( `${wpRequestURL}volumes?_embed=wp:featuredmedia&_fields=${fields}&per_page=${volumePerPage}&page=${calendarStates.currentPage}&page=${calendarStates.currentPage}&per_page=${volumePerPage}&orderby=${calendarStates.selectedFormat.value}&search=${calendarStates.search}&month=${calendarStates.currentMonth}&year=${calendarStates.currentYear}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        const data= await response.json();

        const volumes = data.map( (volume: Volume) => {
            const volumeCover = volume._embedded ? volume._embedded['wp:featuredmedia'][0].source_url: undefined;
            return (
                <NovelItem
                    key={volume.id}
                    id={volume.id}
                    link={`${volume.novel_link}?volumeFilter=${volume.id}&formatFilter=${calendarStates.selectedFormat.value.slice(21)}`} novelCover={volumeCover}
                    releaseDate={formatDate(volume.meta[calendarStates.selectedFormat.value][0])} title={formatTitle(volume.title.rendered, true)}
                />
            );
        });
        lastResponseLength.current=volumes.length;

        updatecalendarStates( (prevInfo) => ({
            ...prevInfo,
            list: prevInfo.currentPage === 1 ? volumes : [...prevInfo.list, ...volumes],
            volumesFound: volumes.length>0 ? true : false,
        }));
    };

    const handleSelect = (data: ReactSelectData) => {
        updatecalendarStates( (prevInfo) => ({
            ...prevInfo,
            selectedFormat: data,
            currentPage: 1,
        }));
    };

    const updateSearch = (event: React.SyntheticEvent, value: string) => {
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

    const monthChange = (type: 1|-1) => {
        updatecalendarStates( (prevStates) => ({
            ...prevStates,
            currentYear: prevStates.currentMonth==11 && type == 1 || prevStates.currentMonth==0 && type == -1 ? prevStates.currentYear+type : prevStates.currentYear,
            currentMonth: prevStates.currentMonth == 0 && type == -1 ? 11 :(prevStates.currentMonth+type)%12,
        }));
    };

    return (
        <>
            <div id="upcoming-releases-header">
                <Search updateSearch={updateSearch}/>
                <Select
                    options={options}
                    value={calendarStates.selectedFormat}
                    isClearable={false}
                    onChange={(data: ReactSelectData) => handleSelect(data)}
                    styles={reactSelectStyle}
                />
            </div>
            {calendarStates.search == '' &&
                <div id="month-nav">
                    {calendarStates.currentMonth != getCurrentMonth() && <FontAwesomeIcon
                        id='previous'
                        title='Previous Month'
                        icon={faLeftLong}
                        size="3x"
                        style={{color: COLORS.primary}}
                        onClick={() => monthChange(-1)}
                    />}
                    <h3>{`${getCurrentMonthNameByNo(calendarStates.currentMonth)}, ${calendarStates.currentYear}`}</h3>
                    <FontAwesomeIcon
                        id='next'
                        title='Next Month'
                        icon={faRightLong}
                        size="3x"
                        style={{color: COLORS.primary}}
                        onClick={() => monthChange(+1)}
                    />
                </div>
            }
            <div className='calendar-list row'>
                {calendarStates.list}
                {!calendarStates.volumesFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView} displayLoader={calendarStates.displayInfiniteLoader && calendarStates.volumesFound}/>
        </>
    );
};

Calendar.propTypes = {
    formatsList: PropTypes.array.isRequired,
};

export default Calendar;
