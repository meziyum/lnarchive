
import React from 'react';
import PropTypes from 'prop-types';
import ReactSelectData from '../types/ReactSelectData';
import Select from 'react-select';
import {reactSelectStyle} from '../style/reactSelectStyles';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {
    faXmark,
} from '@fortawesome/free-solid-svg-icons';

/* eslint-disable no-undef */
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const postID = lnarchiveVariables.object_id;
const userNonce = lnarchiveVariables.nonce;
const readingStatus = lnarchiveVariables.reading_status;
const novelProgress = lnarchiveVariables.progress;
const readingLists = lnarchiveVariables.reading_lists;
const readingItemComments = lnarchiveVariables.comments;
/* eslint-enable no-undef */

interface readingList {
    list_id: string;
    name: string;
    present: 0 | 1;
}

interface ReadingListPopupProps {
    maxProgress: number;
    displayReadingList?: boolean;
    handleReadingListVisibility(): void;
}

interface ReadingListPopupStates {
    reading_progress: number;
    novel_status: 'string';
    currentReadingList: Array<ReactSelectData>;
    author_comments: string;
}

const ReadingListPopup: React.FC<ReadingListPopupProps> = ({maxProgress, displayReadingList=true, handleReadingListVisibility} :ReadingListPopupProps) => {
    const [readingListPopupStates, updateReadingListPopupStates] = React.useState<ReadingListPopupStates>({
        reading_progress: novelProgress == null ? 0 : parseInt(novelProgress),
        novel_status: readingStatus == null ? 'none' : readingStatus,
        currentReadingList: readingLists.filter((readingList: readingList) => readingList.present).map( (readingList: readingList) => ({
            value: readingList.list_id,
            label: readingList.name,
        })),
        author_comments: readingItemComments == null ? '' : readingItemComments,
    });

    const updateReadingList = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        handleReadingListVisibility();
        const changedLists = readingLists.filter( (readingList: readingList) => {
            for (const currentList of readingListPopupStates.currentReadingList) {
                if (currentList.value == readingList.list_id) {
                    return !readingList.present;
                }
            }

            if (readingList.present) {
                return true;
            }
            return false;
        });

        if (readingListPopupStates.reading_progress == novelProgress && readingListPopupStates.novel_status == readingStatus && changedLists.length==0 && readingListPopupStates.author_comments == readingItemComments) {
            return;
        }

        fetch( `${customAPIRequestURL}reading_list`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                object_id: postID,
                status: readingListPopupStates.novel_status,
                progress: readingListPopupStates.reading_progress,
                comments: readingListPopupStates.author_comments,
                lists: displayReadingList ? changedLists.map((readingList: readingList) => ({
                    list_id: readingList.list_id,
                    action: !readingList.present,
                })) : [],
            }),
        });
    };

    const updateForm = (event: React.ChangeEvent<HTMLInputElement> | React.ChangeEvent<HTMLSelectElement> | React.ChangeEvent<HTMLTextAreaElement>) => {
        updateReadingListPopupStates((prevInfo) => ({
            ...prevInfo,
            [event.target.name]: event.target.value,
        }));
    };

    const updateReadingListData = (data: Array<ReactSelectData>) => {
        updateReadingListPopupStates((prevInfo) => ({
            ...prevInfo,
            currentReadingList: data,
        }));
    };

    return (
        <form id="reading-list-action" onSubmit={updateReadingList}>
            <div id='cancel-reading-list'>
                <FontAwesomeIcon
                    title='Cancel'
                    icon={faXmark}
                    size={'2x'}
                    style={{color: 'white'}}
                    onClick={handleReadingListVisibility}
                />
            </div>
            <div>
                <label htmlFor="reading_progress">Progress </label>
                <input name='reading_progress' id='reading_progress' type='number' value={readingListPopupStates.reading_progress} onChange={updateForm} min={0} max={maxProgress}></input>
            </div>
            <div>
                <label htmlFor="novel_status">Reading Status </label>
                <select name="novel_status" id="novel_status" onChange={updateForm} value={readingListPopupStates.novel_status}>
                    <option value='none'>None</option>
                    <option value='plan_to_read'>Plan to Read</option>
                    <option value='reading'>Reading</option>
                    <option value='on_hold'>On Hold</option>
                    <option value='completed'>Completed</option>
                    <option value='dropped'>Dropped</option>
                </select>
            </div>
            <div>
                <textarea id='author_comments' name='author_comments' placeholder='Freely enter your views here.' value={readingListPopupStates.author_comments} onChange={updateForm}/>
            </div>
            {displayReadingList &&
            <>
                <Select
                    placeholder={`Select Reading Lists`}
                    options={
                        readingLists.map((readingList: readingList) => (
                            {
                                value: readingList.list_id,
                                label: readingList.name,
                            }
                        ))
                    }
                    isMulti
                    value={readingListPopupStates.currentReadingList}
                    onChange={updateReadingListData}
                    isClearable={true}
                    styles={reactSelectStyle}
                />
                <button id="update-reading-list">Update</button>
            </>
            }
        </form>
    );
};
export default ReadingListPopup;

ReadingListPopup.propTypes = {
    maxProgress: PropTypes.number.isRequired,
    displayReadingList: PropTypes.bool,
    handleReadingListVisibility: PropTypes.func.isRequired,
};

