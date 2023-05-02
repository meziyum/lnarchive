
import React from 'react';
import * as ReactDOMClient from 'react-dom/client';
import '../../../sass/novel/novel.scss';
import ReviewSection from '../../Components/ReviewSection.jsx';
import NovelActions from './Components/NovelActions.jsx';
import '../common.js';

/* eslint-disable no-undef */
const wpRequestURL = lnarchiveVariables.wp_rest_url+'wp/v2/';
const customAPIrequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsTotalCount = lnarchiveVariables.comments_count;
const loginURL = lnarchiveVariables.login_url;
const novelID = lnarchiveVariables.object_id;
/* eslint-enable no-undef */
const selectedFormatClass = 'selected-format';
const formatButtonClass = 'format-button';
const audiobookFormatClass = 'Audiobook-format';
const reviewsRoot = ReactDOMClient.createRoot(document.getElementById('reviews-section'));
const novelActionsRoot = ReactDOMClient.createRoot(document.getElementById('novel-actions'));
let selectedFormat = document.getElementsByClassName(selectedFormatClass)[0];
const volumesList = document.getElementsByClassName('volume-link');
let isLoggedIn = true;
let maxProgress = 0;

const formatsClickList = (formatButtons) => {
    for ( let i=0; i<formatButtons.length; i++) {
        formatButtons[i].addEventListener('click', function(event) {
            document.getElementById('ISBN_info_value').innerHTML = `<a>${event.target.getAttribute('isbn')}</a>`;
            document.getElementById('Publication Date_info_value').innerHTML = `<a>${event.target.getAttribute('publication_date')}</a>`;
            selectedFormat.classList.remove(selectedFormatClass);
            selectedFormat = event.target;
            selectedFormat.classList.add(selectedFormatClass);
            narratorInfoDisplay();
        });
    }
};

const narratorInfoDisplay = () => {
    if ( document.getElementById(audiobookFormatClass) == undefined || selectedFormat != document.getElementById(audiobookFormatClass)) {
        document.getElementById('Narrator_row').style.display = 'none';
    } else {
        document.getElementById('Narrator_row').style.display = 'table-row';
    }
};

narratorInfoDisplay();
formatsClickList( document.getElementsByClassName(formatButtonClass) );
if (document.getElementById('volumes-no') != null) {
    maxProgress = document.getElementById('volume-list').children.length;
    document.getElementById('volumes-no').innerText=`Volumes - ${maxProgress}`;
}

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

for ( let i=0; i<volumesList.length; i++) {
    volumesList[i].addEventListener('click', function(event) {
        fetch( `${wpRequestURL}volumes/${event.target.parentNode.id}?_embed=wp:featuredmedia,wp:term&_fields=title.rendered,excerpt.rendered,meta,_links`)
            .then( (res) => res.json())
            .then( (data) => {
                document.querySelector('.novel-cover').srcset=data._embedded['wp:featuredmedia']['0'].source_url;
                document.querySelector('.page-title').innerHTML = data.title.rendered;
                document.getElementById('novel-excerpt').innerHTML = data.excerpt.rendered;

                const novelTerms = data._links['wp:term'];

                for (let i=0; i<novelTerms.length; i++) {
                    const taxonomyValues = data._embedded['wp:term'][i];
                    const elementID = `${novelTerms[i].taxonomy}_info_value`;
                    const node = document.getElementById(elementID);

                    if ( node == null) {
                        continue;
                    }

                    node.innerHTML='';

                    for (let j=0; j<taxonomyValues.length; j++) {
                        const taxName = taxonomyValues[j].name;

                        if ( elementID != 'format_info_value') {
                            const taxVal = document.createElement('a');
                            taxVal.innerText = taxName;
                            taxVal.href = taxonomyValues[j].link;
                            node.append(taxVal);
                            node.append(document.createElement('br'));
                        } else {
                            const taxVal = document.createElement('button');
                            taxVal.innerText = taxName;
                            taxVal.className = `${taxName}-format ${formatButtonClass}`;
                            taxVal.setAttribute('isbn', data.meta[`isbn_${taxName}_value`][0] );
                            taxVal.setAttribute( 'publication_date', data.meta[`published_date_value_${taxName}`][0] );
                            node.append(taxVal);

                            if (j==0) {
                                document.getElementById('ISBN_info_value').innerHTML = `<a>${data.meta[`isbn_${taxName}_value`][0]}</a>`;
                                document.getElementById('Publication Date_info_value').innerHTML = `<a>${data.meta[`published_date_value_${taxName}`][0]}</a>`;
                                selectedFormat = document.getElementsByClassName(`${taxName}-format ${formatButtonClass}`)[0];
                                selectedFormat.classList.add(selectedFormatClass);
                            }
                        }
                    }
                }
                narratorInfoDisplay();
                formatsClickList(document.getElementsByClassName(formatButtonClass));
                window.scrollTo(0, 0);
            });
    });
}
