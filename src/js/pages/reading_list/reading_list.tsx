
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/reading_list/reading_list.scss';
import '../common.js';
import ReadingList from './Components/ReadingList';

const readingListDOM = document.getElementById('reading-list-section');

if (readingListDOM) {
    const readingListRoot = ReactDOMClient.createRoot(readingListDOM);
    readingListRoot.render(<ReadingList/>);
} else {
    throw new Error('Unable to Locate the Root to render the components');
}
