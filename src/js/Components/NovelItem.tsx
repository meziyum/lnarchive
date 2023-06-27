
import React from 'react';
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {
    faHeart,
    faFireFlameCurved,
} from '@fortawesome/free-solid-svg-icons';

interface NovelItemProps {
    id: number;
    title?: string;
    rating?: number;
    popularity?: number;
    link: string;
    novelCover?: string;
    releaseDate?: string;
}

/**
 * Renders a single novel item.
 * @param {Object} props - The props object for the component.
 * @param {string} props.id - The unique ID for the novel.
 * @param {string} props.title - The title of the novel.
 * @param {number} props.rating - The title of the novel.
 * @param {number} props.popularity - The title of the novel.
 * @param {string} props.link - The URL for the novel's page.
 * @param {string | null} props.novelCover - The URL for the novel's cover image.
 * @param {string} props.releaseDate - The release date of the novel
 * @return {JSX.Element} - The rendered component.
 */
const NovelItem: React.FC<NovelItemProps> = (props: NovelItemProps) => {
    return (
        <div className="novel-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4">
            <div className="novel-entry archive-entry">
                <a id={props.id.toString()} className="novel-link" href={props.link}>
                    {
                        props.novelCover ?
                            <img className="novel-cover" srcSet={props.novelCover} loading='eager'/> :
                            <h4 className='novel-cover' >No Cover Image Found</h4>
                    }
                    {props.title && <h6 className='novel-title'>{props.title}</h6>}
                </a>
                {!props.releaseDate &&
                    <div className='novel-meta'>
                        <div className="novel-ratings-div">
                            <FontAwesomeIcon
                                title='Ratings'
                                icon={faHeart}
                                size={'sm'}
                                style={{color: '#FF0000'}}
                            />
                            <h4>{props.rating && props.rating>0 ? `${props.rating}%`: '-'}</h4>
                        </div>
                        <div className="novel-popularity-div">
                            <FontAwesomeIcon
                                title='Popularity'
                                icon={faFireFlameCurved}
                                size={'sm'}
                                style={{color: '#FF4500'}}
                            />
                            <h4>{props.popularity ? props.popularity : 0}</h4>
                        </div>
                    </div>
                }
                {props.releaseDate && <h6 className='release-date'>{props.releaseDate}</h6>}
            </div>
        </div>
    );
};

export default React.memo(NovelItem);

NovelItem.propTypes = {
    id: PropTypes.number.isRequired,
    title: PropTypes.string,
    link: PropTypes.string.isRequired,
    novelCover: PropTypes.string,
    releaseDate: PropTypes.string,
    rating: PropTypes.number,
    popularity: PropTypes.number,
};
