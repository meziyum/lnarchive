
import React from 'react';
import Select from 'react-select';
import Pagination from './Pagination.js';
import Novel_Item from './Novel_Item.js';

const params = new URLSearchParams(window.location.search);

const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';

export default function Archive( props ){

    const [appliedFilters, setAppliedFilters] = React.useState(() => {
        const defaults = {};
        props.filter_data.forEach(tax => {
            const options = tax.list.map(term => ({
                value: term.term_id,
                label: term.term_name
            }));
            const query = params.get(`${tax.tax_name}_filter`);
            const defaultValue = options.find(option => option.label === query);
            defaults[tax.tax_name] = query !== null ? [defaultValue] : [];
        });
        return defaults;
    });

    const [ archive_info, update_archive_info] = React.useState({
        novel_list: '',
        novel_filters: props.filter_data.map( tax =>{
            const options = tax.list.map(term => ({
                value: term.term_id,
                label: term.term_name
            }));
            const query = params.get(`${tax.tax_name}_filter`);
            const defaultValue = options.find(option => option.label === query);
            history.replaceState(null, null, window.location.pathname);
            
            return(
                <div key={`${tax.tax_name}_filter`}>
                <h4>{tax.tax_label}</h4>
                <Select
                        placeholder={`Select ${tax.tax_label}`} 
                        options={options}
                        defaultValue={defaultValue}
                        isMulti
                        value={appliedFilters[tax.tax_label]}
                        onChange={ (data) => handleFilter(data, tax.tax_name)}
                        isClearable={true}
                />
                </div>
            )
        }),
        pagination: '',
        current_page: 1,
    });

    React.useEffect( () => {
        get_novels();
    },[ archive_info.current_page, appliedFilters]);

    async function get_novels(){
        console.log('getNovels')
        console.log(appliedFilters)
        let filters=``;
        Object.entries(appliedFilters).forEach(value => {
            const [tax_name, list] = value;
            if( list.length>0){
                let current_filter=`&${tax_name}=`;
                list.forEach(term => {
                    current_filter+=`${term.value},`;
                });
                filters+=current_filter.slice(0, -1);
            }
        });

        let fields = 'id,link,publisher,language,illustrator,genre,tag,novel_status,_links';

        const response = await fetch( `${wp_request_url}novels?_embed=wp:featuredmedia&fields=${fields}&per_page=36&page=${archive_info.current_page}${filters}`);
        const data= await response.json();
        const novels = data.map( novel => {
            return (
                <Novel_Item key={novel.id} id={novel.id} link={novel.link} novel_cover={novel._embedded['wp:featuredmedia'][0].source_url}/>
        )});

        update_archive_info( prev_info => ({
            ...prev_info,
            pagination: <Pagination current_page={archive_info.current_page} length={100} handleclick={handle_page_select}></Pagination>,
            novel_list: novels,
        }));
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
