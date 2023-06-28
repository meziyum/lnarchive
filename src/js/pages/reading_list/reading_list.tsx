
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/reading_list/reading_list.scss';
import '../common.js';
import ReadingList from './Components/ReadingList';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
/* eslint-enable no-undef */
const readingListDOM = document.getElementById('reading-list-main');

if (readingListDOM) {
    const readingListRoot = ReactDOMClient.createRoot(readingListDOM);
    readingListRoot.render(<ReadingList/>);
} else {
    throw new Error('Unable to Locate the Root to render the components')
}
