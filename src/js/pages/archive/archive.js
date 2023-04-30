
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/archive/archive.scss';
import NovelArchive from './Components/NovelArchive.js';
import 'bootstrap/dist/js/bootstrap.js';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
/* eslint-enable no-undef */
const archiveRoot = ReactDOMClient.createRoot(document.getElementById('archive-wrap'));

fetch(`${customAPIRequestURL}novel_filters`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
    }})
    .then( (res) => res.json())
    .then( (data) => {
        archiveRoot.render(<NovelArchive filterData={data}/>);
    });

