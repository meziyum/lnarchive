
//imports
import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

//Import regular Fontawesome icons
import {
    faStar as faStarRegular,
    } 
from '@fortawesome/free-regular-svg-icons';

//Import solid Fontawesome icons
import {    
    faStar as faStarSolid,
    faStarHalfStroke,
    }
from '@fortawesome/free-solid-svg-icons';

export default function Ratings( props ){

    const [hoverRating, setHoverRating] = React.useState(-1);

    const ratingStars = Array.from({ length: props.count}, (_, index) => {

        let number = index + 0.5;

        return(
            <FontAwesomeIcon
                key={index} 
                icon={
                    props.rating >= index +1 && hoverRating==-1 || hoverRating>=index
                    ?
                    faStarSolid
                    :
                    props.rating >= number && (hoverRating>=props || hoverRating==-1)
                    ?
                    faStarHalfStroke
                    :
                    faStarRegular  
                }
                size="lg" 
                style={{ color: props.color }}
                onClick={ () => handleClick(index)}
                onMouseOver={ () => mouseOver(index)}
                onMouseLeave={ mouseLeave}
            />
        )
    })

    function mouseOver( index ){
        if( props.mode == 'form')
        setHoverRating(index)
    }

    function mouseLeave(){
        if( props.mode == 'form')
        setHoverRating(-1)
    }

    function handleClick( index ){

        if( props.mode == 'display')
            return;
        console.log(index+1)
    }

    return(
        <>
            {ratingStars}
        </>
    );
}

//Default Prop Values
Ratings.defaultProps ={
    count: 5,
    rating: 0,
    color: 'orange',
    mode: 'form',
}