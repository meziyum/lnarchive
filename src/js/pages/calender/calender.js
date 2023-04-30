
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/calender/calender.scss';
import Calender from './Components/Calender.js';
import 'bootstrap/dist/js/bootstrap.js';

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
        archiveRoot.render(<Calender formatsList={data}/>);
    });
