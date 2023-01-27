
//Libraries
import './main.js';
import React, { useEffect } from 'react';
import ReactDOM from 'react-dom';
import * as ReactDOMClient from 'react-dom/client';

//Import Styles
import '../sass/archive/archive.scss';

var site_url = window.location.origin;
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.custom_api_url;
const user_nonce = LNarchive_variables.nonce;

const archive_root = ReactDOMClient.createRoot(document.getElementById('archive-wrap'));

document.getElementById("filter-apply").addEventListener('click', function() {

    var tax_list = [ 'novel_status', 'language', 'publisher', 'writer', 'illustrator', ];

    var query="";

    for( var i=0; i<tax_list.length; i++ ){

        var tax_name = tax_list[i];

        if(document.getElementById(tax_name+'_filter_input').value){
            query+=tax_name+"="+document.getElementById('option_'+document.getElementById(tax_name+'_filter_input').value).text+"&"
        }
    }

    query=query.substring(0,query.length-1);
    console.log(query)

    load_archive_novels(wp_request_url+'novels?_embed=wp:featuredmedia&'+query+'&_fields=id,link,_links&per_page=24'); //Load the new list of novels with filters
});

fetch(`${custom_api_request_url}novel_filters`, { //Fetch the comments
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
        query: `${wp_request_url}novels?_embed=wp:featuredmedia&_fields=id,link,_links&per_page=36`,
        novel_list: '',
        novel_filters: '',
    });

    React.useEffect( () =>{

        let filters = props.filter_data.map( tax => {
            return(
            <div key={tax.tax_label}>
                <label htmlFor={`${tax.tax_label}-filter`}></label>

                <select name={`${tax.tax_label}-filter`} id={`${tax.tax_label}-filter`}>
                {tax.list.map( term => {
                    return(
                        <option key={term.term_id} value={term.term_id}>{term.term_name}</option>
                    );
                })
                }
                </select>
            </div>   
        )});

        update_archive_info( prev_info => ({
            ...prev_info,
            novel_filters: filters,
        }));
    }, []);

    React.useEffect( () => {
        get_novels();
    },[]);

    function get_novel_filters(){

    }

    async function get_novels(){
        const response = await fetch( archive_info.query);
        const data= await response.json();
        const novels = data.map( novel => {
            return (
                <Novel key={novel.id} id={novel.id} link={novel.link} novel_cover={novel._embedded['wp:featuredmedia'][0].source_url}/>
        )});

        update_archive_info( prev_info => ({
            ...prev_info,
            novel_list: novels,
        }));
    }

    return(
        <>
            <div className="archive-filter">
                {archive_info.novel_filters}
            </div>
            <div className="archive-list row">
                {archive_info.novel_list}
            </div>
        </>
    );
}

function Novel( props ){ //Function to render a novel entry
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