
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Pagination Component
 *
 * @param {Object} props - Component props
 * @param {number} props.currentPage - Current page number
 * @param {number} props.length - Total number of pages
 * @param {number} [props.siblings=2] - Number of sibling pages to show on either side of the current page
 * @param {function} props.handleclick - Function to handle button click events
 *
 * @return {JSX} - Component JSX
 */
export default function Pagination(props) {
    let pagination=[];
    const currentPage = props.currentPage;
    const length = props.length;
    const siblings = props.siblings;
    let start = currentPage-siblings > 1 ? currentPage-siblings: 1;
    const end = currentPage+siblings > length ? length : currentPage+siblings;

    while ( start<=end) {
        pagination.push(
            <button key={start} value={start} onClick={props.handleclick} className={ start==currentPage ? 'current' : undefined}>{start}</button>,
        );
        start++;
    }

    if (currentPage-siblings>1) {
        pagination=[
            <button key='1' value='1' onClick={props.handleclick}>{'<<'}</button>,
            <button key='...'>{'...'}</button>,
            ...pagination,
        ];
    }

    if (currentPage+siblings<length) {
        pagination=[
            ...pagination,
            <button key='....'>{'...'}</button>,
            <button key={length} value={length} onClick={props.handleclick}>{'>>'}</button>,
        ];
    }

    return (
        <div id="pagination-div">{
            length>1 &&
            <div className="page-list">
                {pagination}
            </div>
        }</div>
    );
}

Pagination.propTypes = {
    currentPage: PropTypes.number.isRequired,
    length: PropTypes.number.isRequired,
    handleclick: PropTypes.func.isRequired,
    siblings: PropTypes.number,
};

Pagination.defaultProps ={
    siblings: 2,
};
