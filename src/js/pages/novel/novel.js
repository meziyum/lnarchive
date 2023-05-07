
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/novel/novel.scss';
import ReviewSection from '../../Components/ReviewSection.jsx';
import FormatsList from './Components/FormatsList.jsx';
import VolumeInfo from './Components/VolumeInfo.jsx';
import VolumeDesc from './Components/VolumeDesc.jsx';
import NovelActions from './Components/NovelActions.jsx';
import '../common.js';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const customAPIrequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;
const novelID = lnarchiveVariables.object_id;
/* eslint-enable no-undef */
const coverRoot = ReactDOMClient.createRoot(document.getElementById('volume-cover'));
const volumeInfoRoot = ReactDOMClient.createRoot(document.getElementById('volume-info'));
const descRoot = ReactDOMClient.createRoot(document.getElementById('volume-desc'));
const formatsRoot = ReactDOMClient.createRoot(document.getElementById('formats-list'));
const reviewsRoot = ReactDOMClient.createRoot(document.getElementById('reviews-section'));
const novelActionsRoot = ReactDOMClient.createRoot(document.getElementById('novel-actions'));
const volumesList = document.getElementsByClassName('volume-link');
let currentVolumeID = document.getElementsByClassName('volume-link')[0].id;
let isLoggedIn = true;
const maxProgress = volumesList.length;
const fields = `excerpt.rendered,featuredmedia,meta,title.rendered,_links`;

fetch( `${customAPIrequestURL}current_user/${novelID}`, {
    headers: {
        'X-WP-Nonce': userNonce,
    },
})
    .then( (res) => res.json())
    .then( (data) => {
        if (data.data != undefined && data.data.status == 401) {
            isLoggedIn = false;
        }
        novelActionsRoot.render(<NovelActions isLoggedIn={isLoggedIn} rating={parseInt(data.user_rating)}/>);
        reviewsRoot.render(<ReviewSection isLoggedIn={isLoggedIn} userID={data.user_id} loginURL={loginURL} commentType='review' commentsCount={parseInt(commentsTotalCount)} maxProgress={maxProgress}/>);
    });

const loadVolumeInfo = (isbn, publishedDate, translator, narrator, formatName) => {
    volumeInfoRoot.render(<VolumeInfo isbn={isbn} publishedDate={publishedDate} translator={translator} narrator={narrator} formatName={formatName}/>);
};

const getVolume = () => {
    fetch( `${wpRequestURL}volumes/${currentVolumeID}?_embed=wp:featuredmedia,wp:term&_fields=${fields}`, {
        headers: {
            'X-WP-Nonce': userNonce,
        },
    })
        .then( (res) => res.json())
        .then( (volume) => {
            const formats = volume._embedded['wp:term'][0]; ///////
            const defaultFormat = formats[0];
            const defaultFormatName = defaultFormat.name;
            const coverURL = volume._embedded['wp:featuredmedia'][0].source_url;
            const desc = volume.excerpt.rendered;
            const narrator = volume._embedded['wp:term'][2]; /////
            const translator = volume._embedded['wp:term'][1]; //////
            const volumeISBN = volume.meta[`isbn_${defaultFormatName}_value`][0];
            const volumeDate = volume.meta[`published_date_value_${defaultFormatName}`][0];

            document.getElementById('page-title').innerText=volume.title.rendered;
            coverRoot.render(<img className='novel-cover' srcSet={coverURL}></img>);
            descRoot.render(<VolumeDesc desc={desc}/>);
            formatsRoot.render(<FormatsList formats={formats} meta={volume.meta} translator={translator} narrator={narrator} handleClick={loadVolumeInfo}/>);
            loadVolumeInfo(volumeISBN, volumeDate, translator, narrator, defaultFormatName);
        });
};
getVolume();

for (let i=0; i<volumesList.length; i++) {
    volumesList[i].addEventListener('click', function(event) {
        currentVolumeID = event.target.parentNode.id;
        getVolume();
        window.scrollTo(0, 0);
    });
}
