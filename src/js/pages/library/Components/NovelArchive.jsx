
import React from 'react';
import PropTypes from 'prop-types';
import NovelItem from '../../../Components/NovelItem.jsx';
import FilterSelect from '../../../Components/FilterSelect.jsx';
import Search from '../../../Components/Search.jsx';
import useToggle from '../../../hooks/useToggle.js';
import InfiniteScroll from '../../../extensions/InfiniteScroll.js';
import ResultsNotFound from '../../../Components/ResultsNotFound.jsx';
import Select from 'react-select';
import {reactSelectStyle} from '../../../helpers/reactSelectStyles.js';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';

import {
    faSliders,
}
    from '@fortawesome/free-solid-svg-icons';

const urlParams = new URLSearchParams(window.location.search);
/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const novelPerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
Renders a page displaying a list of novels with filtering and sorting functionality
@param {Object} props - Component props
@param {Array} props.filterData - An array of objects representing the available filters for the novel archive
@return {JSX.Element} - Rendered NovelArchive component
*/
function NovelArchive(props) {
    const defaultApplitedFilters = () => {
        if (urlParams.entries().next().value !== undefined) {
            const filterName = urlParams.entries().next().value[0];
            const filterValue = urlParams.entries().next().value[1];
            const taxName = filterName.slice(0, -7);
            const tax = props.filterData.find( (tax) => tax.taxQueryName === taxName);

            if (tax) {
                const defaultValue = tax.list.find((option) => option.term_name === filterValue);
                toggleFilters();
                return {
                    [taxName]: [{value: defaultValue.term_id, label: defaultValue.label}],
                };
            }
        }
        return {};
    };

    const defaultSearchValue = () => {
        const searchValue = urlParams.get('searchFilter');

        if (searchValue) {
            return searchValue;
        } else {
            return '';
        }
    };

    const [showFilters, toggleFilters] = useToggle();
    const lastResponseLength = React.useRef(0);
    const [appliedFilters, setAppliedFilters] = React.useState(defaultApplitedFilters);
    const [archiveInfo, updateArchiveInfo] = React.useState({
        novel_list: '',
        novelsFound: true,
        currentPage: 1,
        search: defaultSearchValue(),
        order: {value: 'asc', label: 'Ascending'},
        order_by: {value: 'date', label: 'Release Date'},
    });

    React.useEffect( () => {
        getNovels();
    }, [archiveInfo.currentPage, archiveInfo.order_by, archiveInfo.order, archiveInfo.search, appliedFilters]);

    const getNovels = async () => {
        let filters=``;
        Object.entries(appliedFilters).forEach((value) => {
            const [taxName, list] = value;
            if (list.length>0) {
                let currentFilter= `&${taxName}=`;
                list.forEach((term) => {
                    currentFilter+=`${term.value},`;
                });
                filters+=currentFilter.slice(0, -1);
            }
        });

        const fields = 'id,link,_links.wp:featuredmedia';

        const response = await fetch( `${wpRequestURL}novels?_embed=wp:featuredmedia&_fields=${fields}&per_page=${novelPerPage}&page=${archiveInfo.currentPage}${filters}&order=${archiveInfo.order.value}&orderby=${archiveInfo.order_by.value}&search=${archiveInfo.search}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            }},
        );

        const data= await response.json();
        const novels = data.map( (novel) => {
            return (
                <NovelItem key={novel.id} id={novel.id} link={novel.link} novelCover={novel._embedded['wp:featuredmedia'][0].source_url}/>
            );
        });
        lastResponseLength.current=novels.length;

        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            novel_list: prevInfo.currentPage === 1 ? novels : [...prevInfo.novel_list, novels],
            novelsFound: novels.length>0 ? true : false,
        }));
        history.replaceState(null, null, window.location.pathname);
    };

    const handleSelect = (data, name) => {
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            currentPage: 1,
            [name]: data,
        }));
    };

    const updateSearch = (event, value) => {
        event.preventDefault();
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            currentPage: 1,
            search: value,
        }));
    };

    const handleFilter = ( data, name ) => {
        setAppliedFilters( (prevInfo) => ({
            ...prevInfo,
            [name]: data,
        }));
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            currentPage: 1,
        }));
    };

    const handleInView = () => {
        if (lastResponseLength.current==novelPerPage) {
            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                currentPage: ++prevInfo.currentPage,
            }));
        }
    };

    return (
        <>
            <div id="archive-header">
                <Search value={archiveInfo.search} updateSearch={updateSearch}/>
                <FontAwesomeIcon
                    icon={faSliders}
                    size="xl"
                    style={{color: showFilters ? '#387ef2' : 'grey'}}
                    onClick={toggleFilters}
                />
            </div>
            {showFilters &&
                <div id="archive-filter">
                    {props.filterData.map( (tax) =>{
                        return (
                            <FilterSelect key={`${tax.taxQueryName}_filter`} {...tax} handleFilter={handleFilter} selectValue={appliedFilters[tax.taxLabel]}/>
                        );
                    })}
                    <h6>Sort by</h6>
                    <div id="sort_by">
                        <Select
                            options={[
                                {value: 'date', label: 'Release Date'},
                                {value: 'title', label: 'Alphabetically'},
                            ]}
                            defaultValue={archiveInfo.order_by}
                            value={archiveInfo.order_by}
                            onChange={ (data) => handleSelect(data, 'order_by')}
                            isClearable={false}
                            styles={reactSelectStyle}
                        />
                        <Select
                            options={[
                                {value: 'asc', label: 'Ascending'},
                                {value: 'desc', label: 'Descending '},
                            ]}
                            defaultValue='asc'
                            value={archiveInfo.order}
                            onChange={ (data) => handleSelect(data, 'order')}
                            isClearable={false}
                            styles={reactSelectStyle}
                        />
                    </div>
                </div>
            }
            <div className="archive-list row">
                {archiveInfo.novelsFound && archiveInfo.novel_list}
                {!archiveInfo.novelsFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView}/>
        </>
    );
}

export default NovelArchive;

NovelArchive.propTypes = {
    filterData: PropTypes.array.isRequired,
};
