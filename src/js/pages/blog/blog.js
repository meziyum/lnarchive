
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/blog/blog.scss';
import '../common.js';
import PostArchive from './Components/PostArchive.jsx';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
/* eslint-enable no-undef */
const blogRoot = ReactDOMClient.createRoot(document.getElementById('blog-wrap'));

blogRoot.render(<PostArchive/>);
