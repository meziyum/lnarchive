
import React from 'react';
import Select from 'react-select';
import Pagination from './Pagination.js';
import Novel_Item from './Novel_Item.js';

const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';

export default function Archive( props ){

    const [ archive_info, update_archive_info] = React.useState({
        novel_list: '',
        novel_filters: '',
        pagination: '',
        current_page: 1,
    });

    const [ applied_filters, update_filters] = React.useState({});

    React.useEffect( () =>{

        let filters = props.filter_data.map( tax =>{
            return(
                <div key={`${tax.tax_name}_filter`}>
                <h4>{tax.tax_label}</h4>
                <Select
                        placeholder={`Select ${tax.tax_label}`} 
                        options={ tax.list.map( term => {
                            return(
                                { value: term.term_id, label: term.term_name}
                            );
                        })}
                        isMulti
                        value={applied_filters[tax.tax_label]}
                        onChange={ (data) => handleFilter(data, tax.tax_name)}
                />
                </div>
            )
        });

        update_archive_info( prev_info => ({
            ...prev_info,
            novel_filters: filters,
        }));

        let filter_defaults = {};

        props.filter_data.forEach(element => {
            filter_defaults = {
                ...filter_defaults, 
                [element.tax_name]: [],
            }
        });

        update_filters(filter_defaults);
    }, []);

    React.useEffect( () => {
        get_novels();
    },[ archive_info.current_page, applied_filters]);

    async function get_novels(){

        let filters=``;
        Object.entries(applied_filters).forEach(value => {
            const [tax_name, list] = value;
            if( list.length>0){
                let current_filter=`&${tax_name}=`;
                list.forEach(term => {
                    current_filter+=`${term.value}`;
                });
                filters+=current_filter;
            }
        });

        let fields = 'id,link,publisher,language,illustrator,genre,tag,novel_status,_links';

        const response = await fetch( `${wp_request_url}novels?_embed=wp:featuredmedia&fields=${fields}&per_page=36&page=${archive_info.current_page}${filters}&tax_relation=AND`);
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
        update_filters( prev_info => ({
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
