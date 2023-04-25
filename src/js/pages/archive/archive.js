
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/archive/archive.scss';
import Novel_Archive from './Components/Novel_Archive.js';

const custom_api_request_url = LNarchive_variables.custom_api_url;
const archive_root = ReactDOMClient.createRoot(document.getElementById('archive-wrap'));

fetch(`${custom_api_request_url}novel_filters`, {
    method: "GET",
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
    }})
.then( res => res.json())
.then( data => {
    archive_root.render(<Novel_Archive filter_data={data}/>);
})
