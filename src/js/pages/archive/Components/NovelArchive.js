
import React from 'react';
import PropTypes from 'prop-types';
import Pagination from '../../../Components/Pagination.js';
import NovelItem from '../../../Components/NovelItem.js';
import FilterSelect from './FilterSelect.js';
import NovelSearch from '../../../Components/NovelSearch.js';
import ResultsNotFound from '../../../Components/ResultsNotFound.js';
import Select from 'react-select';
import {reactSelectStyle} from '../../../helpers/reactSelectStyles.js';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';

import {
    faSliders,
}
    from '@fortawesome/free-solid-svg-icons';

const params = new URLSearchParams(window.location.search);
/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url+'wp/v2/';
const novelPerPage = lnarchiveVariables.per_page;
const novelCount = lnarchiveVariables.novel_count;
/* eslint-enable no-undef */

/**
Renders a page displaying a list of novels with filtering and pagination functionality
@param {Object} props - Component props
@param {Array} props.filterData - An array of objects representing the available filters for the novel archive
@return {JSX.Element} - Rendered NovelArchive component
*/
export default function NovelArchive( props ) {
    const defaultApplitedFilters = () => {
        const defaults = {};
        props.filterData.forEach((tax) => {
            const options = tax.list.map((term) => ({
                value: term.term_id,
                label: term.term_name,
            }));
            const query = params.get(`${tax.taxQueryName}_filter`);
            const defaultValue = options.find((option) => option.label === query);
            defaults[tax.taxQueryName] = query !== null ? [defaultValue] : [];
            if (query != undefined) {
                setFilterToggle(true);
            }
        });
        return defaults;
    };

    const handleFilter = ( data, name ) => {
        setAppliedFilters( (prevInfo) => ({
            ...prevInfo,
            [name]: data,
        }));
    };

    const [filterToggleState, setFilterToggle] = React.useState(false);

    const [appliedFilters, setAppliedFilters] = React.useState(defaultApplitedFilters);

    const [archiveInfo, updateArchiveInfo] = React.useState({
        novel_list: '',
        novelsFound: true,
        novel_filters: props.filterData.map( (tax) =>{
            return (
                <FilterSelect key={`${tax.taxQueryName}_filter`} {...tax} handleFilter={handleFilter} selectValue={appliedFilters[tax.taxLabel]}/>
            );
        }),
        pagination: '',
        current_page: 1,
        search: '',
        order: {value: 'asc', label: 'Ascending'},
        order_by: {value: 'date', label: 'Release Date'},
    });

    React.useEffect( () => {
        getNovels();
    }, [archiveInfo.current_page, archiveInfo.order_by, archiveInfo.order, archiveInfo.search, appliedFilters]);

    const getNovels = async () => {
        let filters=``;
        Object.entries(appliedFilters).forEach((value) => {
            const [taxName, list] = value;
            if (list.length>0) {
                let currentFilter= taxName !== 'post_tag' ? `&${taxName}=` : `&post`;
                list.forEach((term) => {
                    currentFilter+=`${term.value},`;
                });
                filters+=currentFilter.slice(0, -1);
            }
        });

        const fields = 'id,link,_links.wp:featuredmedia';

        const response = await fetch( `${wpRequestURL}novels?_embed=wp:featuredmedia&_fields=${fields}&per_page=${novelPerPage}&page=${archiveInfo.current_page}${filters}&order=${archiveInfo.order.value}&orderby=${archiveInfo.order_by.value}&search=${archiveInfo.search}`, {
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

        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            pagination: <Pagination currentPage={archiveInfo.current_page} length={Math.ceil(novelCount/novelPerPage)} handleclick={handlePageSelect}></Pagination>,
            novel_list: novels,
            novelsFound: novels.length>0 ? true : false,
        }));
        history.replaceState(null, null, window.location.pathname);
    };

    const handleSelect = (data, name) => {
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            [name]: data,
        }));
    };

    const handlePageSelect = ( event ) => {
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            current_page: parseInt(event.target.value),
        }));
    };

    const updateSearch = (event, value) => {
        event.preventDefault();
        updateArchiveInfo( (prevInfo) => ({
            ...prevInfo,
            search: value,
        }));
    };

    return (
        <>
            <div id="archive-header">
                <NovelSearch updateSearch={updateSearch}/>
                <FontAwesomeIcon
                    icon={faSliders}
                    size="xl"
                    style={{color: filterToggleState ? '#387ef2' : 'grey'}}
                    onClick={() => {
                        setFilterToggle(!filterToggleState);
                    }}
                />
            </div>
            {filterToggleState &&
                <div id="archive-filter">
                    {archiveInfo.novel_filters}
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
                {archiveInfo.novelsFound && archiveInfo.pagination}
                {!archiveInfo.novelsFound && <ResultsNotFound/>
                }
            </div>
        </>
    );
}

NovelArchive.propTypes = {
    filterData: PropTypes.array.isRequired,
};

