
import React from 'react';
import Pagination from './Pagination.js';
import Novel_Item from './Novel_Item.js';
import Filter_Select from './Filter_Select.js';

const params = new URLSearchParams(window.location.search);
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const novelPerPage = LNarchive_variables.per_page;
const novelCount = LNarchive_variables.novel_count;

export default function Archive( props ){

    const [appliedFilters, setAppliedFilters] = React.useState(defaultApplitedFilters);

    const [ archive_info, update_archive_info] = React.useState({
        novel_list: '',
        novel_filters: props.filter_data.map( tax =>{
            return(
                <Filter_Select key={`${tax.tax_query_name}_filter`} {...tax} handleFilter={handleFilter} selectValue={appliedFilters[props.tax_label]}/>
            )
        }),
        pagination: '',
        current_page: 1,
    });

    React.useEffect( () => {
        get_novels();
    },[ archive_info.current_page, appliedFilters]);

    async function get_novels(){
        let filters=``;
        Object.entries(appliedFilters).forEach(value => {
            const [tax_name, list] = value;
            if( list.length>0){
                let current_filter= tax_name !== 'post_tag' ? `&${tax_name}=` : `&post`;
                list.forEach(term => {
                    current_filter+=`${term.value},`;
                });
                filters+=current_filter.slice(0, -1);
            }
        });

        let fields = 'id,link,publisher,language,illustrator,genre,tag,novel_status,_links';

        const response = await fetch( `${wp_request_url}novels?_embed=wp:featuredmedia&fields=${fields}&per_page=${novelPerPage}&page=${archive_info.current_page}${filters}`, {
            method: "GET",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            }}
        );
        const data= await response.json();
        const novels = data.map( novel => {
            return (
                <Novel_Item key={novel.id} id={novel.id} link={novel.link} novel_cover={novel._embedded['wp:featuredmedia'][0].source_url}/>
        )});

        update_archive_info( prev_info => ({
            ...prev_info,
            pagination: <Pagination current_page={archive_info.current_page} length={Math.ceil(novelCount/novelPerPage)} handleclick={handle_page_select}></Pagination>,
            novel_list: novels,
        }));
        history.replaceState(null, null, window.location.pathname);
    }

    function handleFilter( data, name ){
        setAppliedFilters( prev_info => ({
            ...prev_info,
            [name]: data,
        }));
    }

    function handle_page_select( event ){
        update_archive_info( prev_info => ({
            ...prev_info,
            current_page: parseInt(event.target.value),
        }));
    }

    function defaultApplitedFilters(){
        const defaults = {};
        props.filter_data.forEach(tax => {
            const options = tax.list.map(term => ({
                value: term.term_id,
                label: term.term_name
            }));
            const query = params.get(`${tax.tax_query_name}_filter`);
            const defaultValue = options.find(option => option.label === query);
            defaults[tax.tax_query_name] = query !== null ? [defaultValue] : [];
        });
        return defaults;
    }

    return(
        <>
            <div className="archive-filter">
                {archive_info.novel_filters}
            </div>
            <div className="archive-list row">
                {archive_info.novel_list}
                {archive_info.pagination}
            </div>
        </>
    );
}
