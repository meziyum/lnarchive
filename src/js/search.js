
//Imports
import './main.js';
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../sass/search/search.scss';

//Localised Constants from Server
const wp_request_url = LNarchive_variables.wp_rest_url+'wp/v2/';
const custom_api_request_url = LNarchive_variables.custom_api_url;
const user_nonce = LNarchive_variables.nonce;
const comments_total_count = LNarchive_variables.comments_count;
const login_url = LNarchive_variables.login_url;
const post_id = LNarchive_variables.object_id;

//Class Constants
const search_results_root = ReactDOMClient.createRoot(document.getElementById('search-results'));