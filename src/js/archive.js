
//Libraries
import './main.js';
import React, { useEffect } from 'react';
import Select from 'react-select';
import Pagination from './Components/Pagination.js';
import * as ReactDOMClient from 'react-dom/client';

//Import Styles
import '../sass/archive/archive.scss';

const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.custom_api_url;
const user_nonce = LNarchive_variables.nonce;

const archive_root = ReactDOMClient.createRoot(document.getElementById('archive-wrap'));

fetch(`${custom_api_request_url}novel_filters`, { //Fetch list of novel filters
    method: "GET", //Method
    credentials: 'same-origin', //Send Credentials
    headers: { //Actions on the HTTP Request
        'Content-Type': 'application/json',
        'X-WP-Nonce' : user_nonce,
    }})
.then( res => res.json())
.then( data => {
    archive_root.render(<Archive filter_data={data}/>);
})

function Archive( props ){

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

        let filters='';
        Object.entries(applied_filters).forEach(value => {
            const [tax_name, list] = value;
            if( list.length>0){
                let current_filter='';
                list.forEach(term => {
                    current_filter+=`&${tax_name}=${term.value}`;
                });
                filters+=current_filter;
            }
        });

        let fields = 'id,link,publisher,language,illustrator,genre,tag,novel_status,_links';

        const response = await fetch( `${wp_request_url}novels?_embed=wp:featuredmedia&fields=${fields}&per_page=36&page=${archive_info.current_page}${filters}`);
        const data= await response.json();
        const novels = data.map( novel => {
            return (
                <Novel key={novel.id} id={novel.id} link={novel.link} novel_cover={novel._embedded['wp:featuredmedia'][0].source_url}/>
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

function Novel( props ){
    return (
        <div className="novel-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4">
            <div className="novel-entry archive-entry">
                <a id={props.id} className="novel-link" href={props.link}>
                    <img className="novel-cover" width="900" height="1280" srcSet={props.novel_cover}>
                    </img>
                </a>
            </div>
        </div>
    );
}