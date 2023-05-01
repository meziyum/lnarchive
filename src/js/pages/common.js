
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import WebsiteSearch from '../Components/WebsiteSearch.js';
import 'bootstrap/dist/js/bootstrap.js';

const mainSearchRoot = ReactDOMClient.createRoot(document.getElementById('main-search'));

mainSearchRoot.render(<WebsiteSearch/>);
