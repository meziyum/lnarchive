
//Import the Libraries
import React from 'react';
import ReactDOM from 'react-dom';

//Import Styles
import '../sass/archive/archive.scss'

var site_url = window.location.origin;
var json_request_url = site_url.concat("/wordpress/wp-json/wp/v2/");

//load_archive_novels(json_request_url+"novels?_embed=wp:featuredmedia&_fields=id,link,_links&per_page=24"); //Load the initial novels

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

    load_archive_novels(json_request_url+'novels?_embed=wp:featuredmedia&'+query+'&_fields=id,link,_links&per_page=24'); //Load the new list of novels with filters
});

function load_archive_novels( query ){ //Fetch the novels for the archive
    fetch( query ) //Fetch APi
        .then( res => res.json()) //The fetch API Response
        .then( data => { //The fetch api data
            document.getElementById('novel-list').innerHTML=null; //Remove the previous novel enteries
            for( var i=0; i<data.length; i++ ){ //Loop through all the fetched enteries
                var novel_entry=document.createElement("div"); //Create a div to render the novel into
                var novel = data[i]; //Current Novel in the loop
                novel_entry.className='novel-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4'; //Assign classes to the created div
                ReactDOM.render( <Novel id={novel.id} link={novel.link} novel_cover={novel._embedded['wp:featuredmedia'][0].source_url} ></Novel>, novel_entry ); //Render the Novel Entry
                document.getElementById('novel-list').append(novel_entry); //Append the rendered novel into the novels list
            }
        })
}

function Novel( props ){ //Function to render a novel entry
    return (
        <div className="novel-entry archive-entry">
            <a id={props.id} className="novel-link" href={props.link}>
                <img className="novel-cover" width="900" height="1280" srcSet={props.novel_cover}>
                </img>
            </a>
        </div>
    );
}