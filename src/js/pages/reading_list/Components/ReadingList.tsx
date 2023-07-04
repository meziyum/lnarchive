
import React from 'react';
import PropTypes from 'prop-types';
import ReadingListItemType from '../../../types/ReadingListItemType';
import ReadingListItem from './ReadingListItem';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
/* eslint-enable no-undef */
const params = new URLSearchParams(window.location.search);
const ListId = params.get(`list_id`);

interface ReadingListProps {
}

interface ReadingListStates {
    novels: Array<ReadingListItemType>;
}

const ReadingList: React.FC<ReadingListProps> = ({}: ReadingListProps) => {
    const [readingListStates, updateReadingListState] = React.useState<ReadingListStates>({
        novels: [],
    });

    React.useEffect(() =>{
        getReadingListItems();
    }, []);

    const getReadingListItems = async () => {
        const response = await fetch( `${customAPIRequestURL}reading_list/${ListId}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
        });
        const novels = await response.json();

        updateReadingListState( (prevInfo) => ({
            ...prevInfo,
            novels: novels.map((novel: ReadingListItemType) => {
                return (
                    <ReadingListItem key={novel.ID} {...novel} showProgress={novel.progress ? true : false} showStatus={novel.status ? true : false} showRating={novel.rating ? true : false}/>
                );
            }),
        }));
    };
    return (
        <>
            {readingListStates.novels}
        </>
    );
};
export default ReadingList;

ReadingList.propTypes = {
};
