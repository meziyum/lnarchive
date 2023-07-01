
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/novel/novel.scss';
import ReviewSection from '../../Components/ReviewSection';
import FormatsList from './Components/FormatsList';
import VolumeInfo from './Components/VolumeInfo';
import VolumeDesc from './Components/VolumeDesc';
import NovelActions from './Components/NovelActions';
import '../common.js';
import {formatDate, formatTitle} from '../../helpers/utilities';
import VolumeType from '../../types/VolumeType';
import TermType from '../../types/TermType';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url;
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsEnabled = lnarchiveVariables.commentsEnabled;
const commentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;

const userID = parseInt(lnarchiveVariables.user_id);
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
const novelRating = lnarchiveVariables.rating;
const novelPopularity = lnarchiveVariables.popularity;
/* eslint-enable no-undef */
const volumesList = document.getElementsByClassName('volume-link');
const urlParams = new URLSearchParams(window.location.search);
if (!urlParams.get('volumeFilter')) {
    urlParams.set('volumeFilter', document.getElementsByClassName('volume-link')[0].id);
}

const maxProgress = volumesList.length;
const fields = `id,excerpt.rendered,featuredmedia,meta,title.rendered,_links`;

const novelActionsDOM = document.getElementById('novel-actions');

if (novelActionsDOM) {
    fetch(`${customAPIRequestURL}reading_lists/${userID}`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': userNonce,
        },
    })
        .then( (res) => res.json())
        .then( (readingLists) => {
            const novelActionsRoot = ReactDOMClient.createRoot(novelActionsDOM);
            novelActionsRoot.render(<NovelActions isLoggedIn={isLoggedIn} novelRating={parseInt(novelRating)} novelPopularity={parseInt(novelPopularity)} readingLists={readingLists} maxProgress={maxProgress}/>);
        });
} else {
    throw new Error('Unable to find the Novel Actions Root');
}

if (commentsEnabled) {
    const reviewTargetDOM = document.getElementById('reviews-section');

    if (reviewTargetDOM) {
        const reviewsRoot = ReactDOMClient.createRoot(reviewTargetDOM);
        reviewsRoot.render(<ReviewSection isLoggedIn={isLoggedIn} userID={userID} loginURL={loginURL} commentType='review' commentsCount={parseInt(commentsTotalCount)} maxProgress={maxProgress}/>);
    } else {
        throw new Error('Unable to find the Reviews Root to render');
    }
}

const loadVolumeInfo = (isbn: string, publishedDate: string, translator: Array<TermType>, narrator: Array<TermType>, formatName: string) => {
    const volumesInfoDOM = document.getElementById('volume-info');
    if (volumesInfoDOM) {
        const volumeInfoRoot = ReactDOMClient.createRoot(volumesInfoDOM);
        volumeInfoRoot.render(<VolumeInfo isbn={isbn} publishedDate={publishedDate} translator={translator} narrator={narrator} formatName={formatName}/>);
    } else {
        throw new Error('Unable to find the Volumes Info Root');
    }
};

const getVolume = () => {
    fetch( `${wpRequestURL}volumes/${urlParams.get('volumeFilter')}?_embed=wp:featuredmedia,wp:term&_fields=${fields}`, {
        headers: {
            'X-WP-Nonce': userNonce,
        },
    })
        .then( (res) => res.json())
        .then( (volume: VolumeType) => {
            const formats= volume._embedded['wp:term'].find( (term) => term[0].taxonomy == 'format') as Array<TermType>;

            if (!formats) {
                throw new Error();
            }

            if (!urlParams.get('formatFilter')) {
                urlParams.set('formatFilter', formats[0].name);
                const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
                window.history.pushState(null, '', newUrl);
            }

            const defaultFormatName = !urlParams.get('formatFilter') ? formats[0].name : urlParams.get('formatFilter') as string;
            const desc: string = volume.excerpt.rendered;
            const narrator = volume._embedded['wp:term'].find( (term) => term[0].taxonomy == 'narrator') as Array<TermType>;
            const translator = volume._embedded['wp:term'].find( (term) => term[0].taxonomy == 'translator') as Array<TermType>;
            const volumeISBN: string = defaultFormatName != 'None' ? volume.meta[`isbn_${defaultFormatName}_value`][0] : '';
            const volumeDate: string = defaultFormatName != 'None' ? formatDate(volume.meta[`published_date_value_${defaultFormatName}`][0]): '';
            const coverURL: string= volume._embedded['wp:featuredmedia'] ? volume._embedded['wp:featuredmedia'][0].source_url : '';
            const titleDOM = document.getElementById('page-title');

            if (titleDOM) {
                titleDOM.innerText=formatTitle(volume.title.rendered, false);
            } else {
                throw new Error('Unable to find the Title Root');
            }

            const coverDOM = document.getElementById('volume-cover');

            if (coverDOM) {
                const coverRoot = ReactDOMClient.createRoot(coverDOM);
                coverRoot.render(<img className='novel-cover' srcSet={coverURL}></img>);
            } else {
                throw new Error('Unable to Find the Cover DOM');
            }

            if (desc) {
                const descDOM = document.getElementById('volume-desc');

                if (descDOM) {
                    const descRoot = ReactDOMClient.createRoot(descDOM);
                    descRoot.render(<VolumeDesc desc={desc}/>);
                } else {
                    throw new Error('Unable to find the Desc Root');
                }
            }

            const formatsDOM = document.getElementById('formats-list');
            if (formatsDOM) {
                const formatsRoot = ReactDOMClient.createRoot(formatsDOM);
                formatsRoot.render(<FormatsList formats={formats} meta={volume.meta} translator={translator} narrator={narrator} handleClick={loadVolumeInfo} formatFilter={defaultFormatName}/>);
            } else {
                throw new Error('Unable to find the Formats Root');
            }

            loadVolumeInfo(volumeISBN, volumeDate, translator, narrator, defaultFormatName);
        });
};
getVolume();

for (let i=0; i<volumesList.length; i++) {
    volumesList[i].addEventListener('click', function(event) {
        const volumeID = ((event.target as HTMLImageElement).parentElement as HTMLAnchorElement).id;

        if (event.target === null || volumeID == urlParams.get('volumeFilter')) {
            return;
        }
        urlParams.delete('formatFilter');
        urlParams.set('volumeFilter', volumeID);
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.history.pushState(null, '', newUrl);
        getVolume();
        window.scrollTo(0, 0);
    });
}
