
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/blog/blog.scss';
import '../common.js';
import PostArchive from './Components/PostArchive.jsx';

const blogRoot = ReactDOMClient.createRoot(document.getElementById('blog-wrap'));

blogRoot.render(<PostArchive/>);
