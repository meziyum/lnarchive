
import React from 'react';
import {FontAwesomeIcon, FontAwesomeIconProps} from '@fortawesome/react-fontawesome';
import PropTypes from 'prop-types';
import {faStar as faStarRegular} from '@fortawesome/free-regular-svg-icons';
import {faStar as faStarSolid, faStarHalfStroke} from '@fortawesome/free-solid-svg-icons';

interface RatingsProps {
    count?: number;
    rating?: number;
    color?: string;
    mode?: 'form' | 'display';
    submitRatings(value: number) : void;
    size?: FontAwesomeIconProps['size'];
}

/**
 * A component for displaying and submitting ratings in a star-based format.
 *
 * @param {Object} props - The properties for the component
 * @param {number} [props.count=5] - The number of stars to display
 * @param {number} [props.rating=0] - The current rating to display
 * @param {string} [props.color='orange'] - The color of the stars
 * @param {string} [props.mode='form'] - The mode of the component, either 'form' or 'display'
 * @param {function} [props.submitRatings] - A callback function to be called when a rating is submitted
 * @param {string} [props.size='lg'] - The size of the stars
 *
 * @return {JSX.Element} - A JSX element representing the ratings component
 */
const Ratings = ({mode='form', count=5, rating=0, color='orange', submitRatings, size='lg'}: RatingsProps) => {
    const mouseOver = (index: number) => {
        if (mode === 'form') setHoverRating(index);
    };

    const mouseLeave = () => {
        if (mode === 'form') setHoverRating(-1);
    };

    const [hoverRating, setHoverRating] = React.useState(-1);

    const ratingStars = Array.from({length: count}, (_, index) => {
        return (
            <FontAwesomeIcon
                key={index}
                title='User Rating'
                icon={
                    rating >= index + 1 && hoverRating === -1 || hoverRating >= index ? faStarSolid :
                        rating >= index + 0.5 && (hoverRating >= rating || hoverRating === -1) ?
                            faStarHalfStroke :
                            faStarRegular
                }
                size={size}
                style={{color: color}}
                onClick={() => submitRatings((index + 1)*20)}
                onMouseOver={() => mouseOver(index)}
                onMouseLeave={mouseLeave}
            />
        );
    });

    return (
        <>
            {(rating !== 0 || mode === 'form') && ratingStars}
        </>
    );
};
export default Ratings;

Ratings.propTypes = {
    count: PropTypes.number,
    rating: PropTypes.number,
    color: PropTypes.string,
    mode: PropTypes.string,
    ratings_submit: PropTypes.func,
    size: PropTypes.string,
};
