
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/novel/novel.scss';
import ReviewSection from '../../Components/ReviewSection.jsx';
import FormatsList from './Components/FormatsList.jsx';
import VolumeInfo from './Components/VolumeInfo.jsx';
import VolumeDesc from './Components/VolumeDesc.jsx';
import NovelActions from './Components/NovelActions.jsx';
import '../common.js';
import {formatDate} from '../../helpers/utilities.ts';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const userNonce = lnarchiveVariables.nonce;
const commentsEnabled = lnarchiveVariables.commentsEnabled;
const commentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;
const userRating = lnarchiveVariables.user_rating;
const userID = parseInt(lnarchiveVariables.user_id);
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
const novelRating = lnarchiveVariables.rating;
const novelPopularity = lnarchiveVariables.popularity;

/* eslint-enable no-undef */
const coverRoot = ReactDOMClient.createRoot(document.getElementById('volume-cover'));
const volumeInfoRoot = ReactDOMClient.createRoot(document.getElementById('volume-info'));
const descRoot = ReactDOMClient.createRoot(document.getElementById('volume-desc'));
const formatsRoot = ReactDOMClient.createRoot(document.getElementById('formats-list'));
const reviewsRoot = ReactDOMClient.createRoot(document.getElementById('reviews-section'));
const novelActionsRoot = ReactDOMClient.createRoot(document.getElementById('novel-actions'));
const volumesList = document.getElementsByClassName('volume-link');
const urlParams = new URLSearchParams(window.location.search);
if (!urlParams.get('volumeFilter')) {
    urlParams.set('volumeFilter', document.getElementsByClassName('volume-link')[0].id);
}

const maxProgress = volumesList.length;
const fields = `excerpt.rendered,featuredmedia,meta,title.rendered,_links`;

novelActionsRoot.render(<NovelActions isLoggedIn={isLoggedIn} novelRating={parseInt(novelRating)} novelPopularity={parseInt(novelPopularity)} userRating={parseInt(userRating)}/>);

if (commentsEnabled) {
    reviewsRoot.render(<ReviewSection isLoggedIn={isLoggedIn} userID={userID} loginURL={loginURL} commentType='review' commentsCount={parseInt(commentsTotalCount)} maxProgress={maxProgress}/>);
}

const loadVolumeInfo = (isbn, publishedDate, translator, narrator, formatName) => {
    volumeInfoRoot.render(<VolumeInfo isbn={isbn} publishedDate={publishedDate} translator={translator} narrator={narrator} formatName={formatName}/>);
};

const getVolume = () => {
    fetch( `${wpRequestURL}volumes/${urlParams.get('volumeFilter')}?_embed=wp:featuredmedia,wp:term&_fields=${fields}`, {
        headers: {
            'X-WP-Nonce': userNonce,
        },
    })
        .then( (res) => res.json())
        .then( (volume) => {
            const formats = volume._embedded['wp:term'].find( (term) => term[0].taxonomy == 'format');

            if (!urlParams.get('formatFilter')) {
                urlParams.set('formatFilter', formats[0].name);
                const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
                window.history.pushState(null, '', newUrl);
            }

            const defaultFormatName = urlParams.get('formatFilter');
            const desc = volume.excerpt.rendered;
            const narrator = volume._embedded['wp:term'].find( (term) => term[0].taxonomy == 'narrator');
            const translator = volume._embedded['wp:term'].find( (term) => term[0].taxonomy == 'translator');
            const volumeISBN = defaultFormatName != 'None' ? volume.meta[`isbn_${defaultFormatName}_value`][0] : null;
            const volumeDate = defaultFormatName != 'None' ? formatDate(volume.meta[`published_date_value_${defaultFormatName}`][0]): null;
            const coverURL= volume._embedded['wp:featuredmedia'] ? volume._embedded['wp:featuredmedia'][0].source_url : null;

            document.getElementById('page-title').innerText=volume.title.rendered;
            coverRoot.render(<img className='novel-cover' srcSet={coverURL}></img>);
            descRoot.render(<VolumeDesc desc={desc}/>);
            formatsRoot.render(<FormatsList formats={formats} meta={volume.meta} translator={translator} narrator={narrator} handleClick={loadVolumeInfo} formatFilter={defaultFormatName}/>);
            loadVolumeInfo(volumeISBN, volumeDate, translator, narrator, defaultFormatName);
        });
};
getVolume();

for (let i=0; i<volumesList.length; i++) {
    volumesList[i].addEventListener('click', function(event) {
        if (event.target.parentNode.id == urlParams.get('volumeFilter')) {
            return;
        }
        urlParams.delete('formatFilter');
        urlParams.set('volumeFilter', event.target.parentNode.id);
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.history.pushState(null, '', newUrl);
        getVolume();
        window.scrollTo(0, 0);
    });
}
