
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
const coverDOM = document.getElementById('volume-cover') as HTMLDivElement;
const coverRoot = ReactDOMClient.createRoot(coverDOM);
const descDOM = document.getElementById('volume-desc') as HTMLDivElement;
const descRoot = ReactDOMClient.createRoot(descDOM);
const formatsDOM = document.getElementById('formats-list') as HTMLDivElement;
const formatsRoot = ReactDOMClient.createRoot(formatsDOM);
const maxProgress = volumesList.length;
const fields = `id,excerpt.rendered,featuredmedia,meta,title.rendered,_links`;
const volumesInfoDOM = document.getElementById('volume-info') as HTMLDivElement;;
const volumeInfoRoot = ReactDOMClient.createRoot(volumesInfoDOM);

const novelActionsDOM = document.getElementById('novel-actions');

if (novelActionsDOM) {
    const novelActionsRoot = ReactDOMClient.createRoot(novelActionsDOM);
    novelActionsRoot.render(<NovelActions isLoggedIn={isLoggedIn} novelRating={parseInt(novelRating)} novelPopularity={parseInt(novelPopularity)} maxProgress={maxProgress}/>);
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
    volumeInfoRoot.render(<VolumeInfo isbn={isbn} publishedDate={publishedDate} translator={translator} narrator={narrator} formatName={formatName}/>);
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

            coverRoot.render(<img className='novel-cover' srcSet={coverURL}></img>);

            if (desc) {
                descRoot.render(<VolumeDesc desc={desc}/>);
            }
            formatsRoot.render(<FormatsList formats={formats} meta={volume.meta} translator={translator} narrator={narrator} handleClick={loadVolumeInfo} formatFilter={defaultFormatName}/>);
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
