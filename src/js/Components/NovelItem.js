
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Renders a single novel item in an archive.
 * @param {Object} props - The props object for the component.
 * @param {string} props.id - The unique ID for the novel.
 * @param {string} props.link - The URL for the novel's page.
 * @param {string} props.novelCover - The URL for the novel's cover image.
 * @param {string} props.releaseDate - The release date of the novel
 * @return {JSX.Element} - The rendered component.
 */
function NovelItem(props) {
    return (
        <div className="novel-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4">
            <div className="novel-entry archive-entry">
                <a id={props.id} className="novel-link" href={props.link}>
                    <img className="novel-cover" width="900" height="1280" srcSet={props.novelCover}>
                    </img>
                </a>
                {props.releaseDate && <h5 className='release-date'>{props.releaseDate}</h5>}
            </div>
        </div>
    );
}

export default React.memo(NovelItem);

NovelItem.propTypes = {
    id: PropTypes.number.isRequired,
    link: PropTypes.string.isRequired,
    novelCover: PropTypes.string.isRequired,
    releaseDate: PropTypes.string,
};
