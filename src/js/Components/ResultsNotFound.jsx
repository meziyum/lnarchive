
import React from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';

import {
    faTriangleExclamation,
}
    from '@fortawesome/free-solid-svg-icons';

/**
 * Renders a message indicating that no results were found for the applied filters.
 * @param {object} props - The component props.
 * @return {JSX.Element} - The rendered component.
 */
export default function ResultsNotFound(props) {
    return (
        <div id="results-not-found">
            <FontAwesomeIcon
                title='Results not Found'
                icon={faTriangleExclamation}
                size="5x"
                style={{color: 'red'}}
            />
            <h2>We were unable to find results for the applied filters</h2>
        </div>
    );
}
