
import React from 'react';
import PropTypes from 'prop-types';
import ReadingListItemType from '../../../types/ReadingListItemType';

interface ReadingListItemProps extends ReadingListItemType {
    showProgress: boolean;
    showRating: boolean;
    showStatus: boolean;
}

const ReadingListItem: React.FC<ReadingListItemProps> = ({ID, progress, rating, status, title, cover, showProgress=false, showRating=false, showStatus=false}: ReadingListItemProps) => {
    return (
        <>
            <div class='reading-list-left'>

            </div>
            <div class='reading-list-right'>
                <h3>{title}</h3>
            </div>
        </>
    );
};
export default ReadingListItem;

ReadingListItem.propTypes = {
    showProgress: PropTypes.bool.isRequired,
    showRating: PropTypes.bool.isRequired,
    showStatus: PropTypes.bool.isRequired,
};
