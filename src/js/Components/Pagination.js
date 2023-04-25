
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Pagination Component
 * 
 * @param {Object} props - Component props
 * @param {number} props.current_page - Current page number
 * @param {number} props.length - Total number of pages
 * @param {number} [props.siblings=2] - Number of sibling pages to show on either side of the current page
 * @param {function} props.handleclick - Function to handle button click events
 * 
 * @returns {JSX} - Component JSX
 */
export default function Pagination(props) { 

    let pagination=[];
    let current_page = props.current_page;
    let length = props.length;
    let siblings = props.siblings;
    let start = current_page-siblings > 1 ? current_page-siblings: 1;
    let end = current_page+siblings > length ? length : current_page+siblings;

    while( start<=end){
        pagination.push(
            <button key={start} value={start} onClick={props.handleclick} className={ start==current_page ? "current" : undefined}>{start}</button>
        );
        start++;
    }

    if( current_page-siblings>1 )
        pagination=[
            <button key='1' value='1' onClick={props.handleclick}>{'<<'}</button>,
            <button key='...'>{'...'}</button>,
            ...pagination,
        ];
    
    if( current_page+siblings<length )
        pagination=[
            ...pagination,
            <button key='....'>{'...'}</button>,
            <button key={length} value={length} onClick={props.handleclick}>{'>>'}</button>,
        ];
    
    return(
        <>{   
            length>1 &&
            <div className="page-list">
                {pagination}
            </div>
        }</>
    );
}

Pagination.propTypes = {
  current_page: PropTypes.number.isRequired,
  length: PropTypes.number.isRequired,
  handleclick: PropTypes.func.isRequired,
  siblings: PropTypes.number
};

Pagination.defaultProps ={
    siblings: 2,
}