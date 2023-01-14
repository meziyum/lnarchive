
import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import PropTypes from 'prop-types';
import { faStar as faStarRegular } from '@fortawesome/free-regular-svg-icons';
import { faStar as faStarSolid, faStarHalfStroke } from '@fortawesome/free-solid-svg-icons';

/**
 * A component for displaying and submitting ratings in a star-based format.
 *
 * @param {Object} props - The properties for the component
 * @param {number} [props.count=5] - The number of stars to display
 * @param {number} [props.rating=0] - The current rating to display
 * @param {string} [props.color='orange'] - The color of the stars
 * @param {string} [props.mode='form'] - The mode of the component, either 'form' or 'display'
 * @param {function} [props.ratings_submit=''] - A callback function to be called when a rating is submitted
 * @param {string} [props.size='lg'] - The size of the stars
 *
 * @returns {JSX.Element} - A JSX element representing the ratings component
 */
export default function Ratings(props) {
    const [hoverRating, setHoverRating] = React.useState(-1); // hover state of the mouse

    // Construct the ratings
    const ratingStars = Array.from({ length: props.count }, (_, index) => {
        return (
            <FontAwesomeIcon
                key={index}
                icon={
                    props.rating >= index + 1 && hoverRating === -1 || hoverRating >= index
                        ? faStarSolid
                        : props.rating >= index + 0.5 && (hoverRating >= props || hoverRating === -1)
                            ? faStarHalfStroke
                            : faStarRegular
                }
                size={props.size}
                style={{ color: props.color }}
                onClick={() => props.ratings_submit(index + 1)}
                onMouseOver={() => mouseOver(index)}
                onMouseLeave={mouseLeave}
            />
        );
    });

    /**
     * A function to update the rating visuals on mouse hover
     *
     * @param {number} index - The index of the star being hovered over
     */
    function mouseOver(index) {
        if (props.mode === 'form') setHoverRating(index);
    }

    /**
     * A function to update the rating visuals on mouse leave
     */
    function mouseLeave() {
        if (props.mode === 'form') setHoverRating(-1);
    }

    return (
        <>
            {(props.rating !== 0 || props.mode === 'form') && ratingStars}
        </>
    );
}

Ratings.propTypes = {
    count: PropTypes.number,
    rating: PropTypes.number,
    color: PropTypes.string,
    mode: PropTypes.string,
    ratings_submit: PropTypes.func,
    size: PropTypes.string,
};

Ratings.defaultProps ={
    count: 5,
    rating: 0,
    color: 'orange',
    mode: 'form',
    ratings_submit: () => {},
    size: 'lg',
}