
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/calendar/calendar.scss';
import Calendar from './Components/Calendar.jsx';
import '../common.js';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
/* eslint-enable no-undef */
const archiveRoot = ReactDOMClient.createRoot(document.getElementById('upcoming-releases-wrap'));

fetch(`${customAPIRequestURL}formats_list`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
    }})
    .then( (res) => res.json())
    .then( (data) => {
        archiveRoot.render(<Calendar formatsList={data}/>);
    });
