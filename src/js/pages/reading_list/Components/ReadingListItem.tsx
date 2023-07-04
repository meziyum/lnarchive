
import React from 'react';
import PropTypes from 'prop-types';
import ReadingListItemType from '../../../types/ReadingListItemType';

interface ReadingListItemProps extends ReadingListItemType {
    showProgress?: boolean;
    showRating?: boolean;
    showStatus?: boolean;
}

const ReadingListItem: React.FC<ReadingListItemProps> = ({ID, progress, rating, status, title, cover, showProgress=true, showRating=true, showStatus=true}: ReadingListItemProps) => {
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
    showProgress: PropTypes.bool,
    showRating: PropTypes.bool,
    showStatus: PropTypes.bool,
};
