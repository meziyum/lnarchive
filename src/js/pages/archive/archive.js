
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/archive/archive.scss';
import NovelArchive from './Components/NovelArchive.js';

/* eslint-disable no-undef, camelcase */
const customAPIRequestURL = LNarchive_variables.custom_api_url;
/* eslint-enable no-undef, camelcase*/
const archiveRoot = ReactDOMClient.createRoot(document.getElementById('archive-wrap'));

fetch(`${customAPIRequestURL}novel_filters`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
    }})
    .then( (res) => res.json())
    .then( (data) => {
        archiveRoot.render(<NovelArchive filter_data={data}/>);
    });

