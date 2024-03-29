
import React from 'react';
import PropTypes from 'prop-types';
import NovelItem from '../../../Components/NovelItem';
import TaxSelect from '../../../Components/TaxSelect';
import Search from '../../../Components/Search';
import useToggle from '../../../hooks/useToggle';
import InfiniteScroll from '../../../extensions/InfiniteScroll';
import {formatTitle} from '../../../helpers/utilities';
import ResultsNotFound from '../../../layouts/ResultsNotFound';
import Select from 'react-select';
import {reactSelectStyle} from '../../../style/reactSelectStyles';
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

interface NovelArchiveProps {
    filterData: Array<{
        taxQueryName: string;
        taxLabel: string;
        list: Array<{
            term_id: number;
            term_name: string;
            label: string;
        }>;
    }>;
}

interface ArchiveInfoStates {
    novel_list: Array<React.JSX.Element>,
    novelsFound: boolean;
    currentPage: number;
    displayInfiniteLoader: boolean;
    search: string,
    order: {
        value: string;
        label: string;
    };
    order_by: {
        value: string;
        label: string;
    },
}

interface Novel {
    id: number;
    link: string;
    title: {
        rendered: string;
    };
    _embedded: {
        'wp:featuredmedia': Array<{
            source_url: string;
        }>
    };
    meta: {
        popularity: number;
        rating: Array<number>;
    }
}

/**
Renders a page displaying a list of novels with filtering and sorting functionality
@param {Object} props - Component props
@param {Array} props.filterData - An array of objects representing the available filters for the novel archive
@return {JSX.Element} - Rendered NovelArchive component
*/
const NovelArchive: React.FC<NovelArchiveProps> = ({filterData} :NovelArchiveProps) => {
    const defaultApplitedFilters = () => {
        if (urlParams.entries().next().value !== undefined) {
            const filterName = urlParams.entries().next().value[0];
            const filterValue = urlParams.entries().next().value[1];
            const taxName = filterName.slice(0, -7);
            const tax = filterData.find( (tax) => tax.taxQueryName == taxName);

            if (tax) {
                const defaultValue = tax.list.find((option) => option.term_id == filterValue);
                toggleFilters();

                if (defaultValue) {
                    return {
                        [taxName]: [{label: defaultValue.term_name, value: defaultValue.term_id}],
                    };
                }
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
    const [archiveInfo, updateArchiveInfo] = React.useState<ArchiveInfoStates>({
        novel_list: [],
        novelsFound: true,
        currentPage: 1,
        displayInfiniteLoader: true,
        search: defaultSearchValue(),
        order: {value: 'desc', label: 'Descending'},
        order_by: {value: 'popularity', label: 'Popularity'},
    });

    React.useEffect( () => {
        getNovels();
    }, [archiveInfo.currentPage, archiveInfo.order_by, archiveInfo.order, archiveInfo.search, appliedFilters]);

    React.useEffect( () => {
        history.replaceState(null, '', window.location.pathname);
    }, []);

    const getNovels = async () => {
        try {
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
            const fields = 'id,title.rendered,meta,link,_links.wp:featuredmedia';

            const response = await fetch( `${wpRequestURL}novels?_embed=wp:featuredmedia&_fields=${fields}&per_page=${novelPerPage}&page=${archiveInfo.currentPage}${filters}&order=${archiveInfo.order.value}&orderby=${archiveInfo.order_by.value}&search=${archiveInfo.search}`, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                }},
            );

            const data= await response.json();

            const novels = data.map( (novel: Novel) => {
                const novelCover=novel._embedded ? novel._embedded['wp:featuredmedia'][0].source_url : '';
                return (
                    <NovelItem key={novel.id} id={novel.id} title={formatTitle(novel.title.rendered, true)} link={novel.link} novelCover={novelCover} popularity={novel.meta.popularity} rating={novel.meta.rating[0]}/>
                );
            });
            lastResponseLength.current=novels.length;
            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                novel_list: prevInfo.currentPage === 1 ? novels : [...prevInfo.novel_list, novels],
                novelsFound: novels.length>0 ? true : false,
            }));
        } catch (error) {
            lastResponseLength.current=0;
        }
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
        } else {
            updateArchiveInfo( (prevInfo) => ({
                ...prevInfo,
                displayInfiniteLoader: false,
            }));
        }
    };

    const handleClear = () => {
        setAppliedFilters({});
        updateArchiveInfo({
            novel_list: [],
            novelsFound: true,
            currentPage: 1,
            displayInfiniteLoader: true,
            search: '',
            order: {value: 'desc', label: 'Descending'},
            order_by: {value: 'popularity', label: 'Popularity'},
        });
    };

    return (
        <>
            <div id="archive-header">
                <Search value={archiveInfo.search} updateSearch={updateSearch}/>
                <FontAwesomeIcon
                    title='Search'
                    icon={faSliders}
                    size="xl"
                    style={{color: showFilters ? '#387ef2' : 'grey'}}
                    onClick={toggleFilters}
                />
            </div>
            {showFilters &&
                <div id="archive-filter">
                    {filterData.map( (tax) =>{
                        return (
                            <TaxSelect key={`${tax.taxQueryName}_filter`} {...tax} handleFilter={handleFilter} selectValue={appliedFilters[tax.taxQueryName] ? appliedFilters[tax.taxQueryName] : null}/>
                        );
                    })}
                    <h6>Sort by</h6>
                    <div id='filter_footer'>
                        <div id="sort_by">
                            <Select
                                options={[
                                    {value: 'latest_release', label: 'Latest'},
                                    {value: 'rating', label: 'Ratings'},
                                    {value: 'popularity', label: 'Popularity'},
                                    {value: 'no_of_volumes', label: 'Volumes'},
                                    {value: 'first_release', label: 'First Release'},
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
                                defaultValue={{value: 'asc', label: 'Ascending'}}
                                value={archiveInfo.order}
                                onChange={ (data) => handleSelect(data, 'order')}
                                isClearable={false}
                                styles={reactSelectStyle}
                            />
                        </div>
                        <div id='filter_actions'>
                            <button id='clear' onClick={handleClear}>Clear All</button>
                        </div>
                    </div>
                </div>
            }
            <div className="archive-list row">
                {archiveInfo.novelsFound && archiveInfo.novel_list}
                {!archiveInfo.novelsFound && <ResultsNotFound/>}
            </div>
            <InfiniteScroll handleInView={handleInView} displayLoader={archiveInfo.displayInfiniteLoader && archiveInfo.novelsFound}/>
        </>
    );
};

export default NovelArchive;

NovelArchive.propTypes = {
    filterData: PropTypes.array.isRequired,
};

