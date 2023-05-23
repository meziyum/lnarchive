
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/add_data/add_data.scss';
import '../common.js';
import AddDataForm from './Components/AddDataForm.tsx';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
/* eslint-enable no-undef */
const addDataRoot = ReactDOMClient.createRoot(document.getElementById('add-data-main'));

const novelResponse = await fetch(`${customAPIRequestURL}novel_filters`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
    }});
const novelFilters = await novelResponse.json();

const volumeResponse = await fetch(`${customAPIRequestURL}volume_filters`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
    }});
const volumeFilters = await volumeResponse.json();

addDataRoot.render(<AddDataForm novelFilters={novelFilters} volumeFilters={volumeFilters}/>);
