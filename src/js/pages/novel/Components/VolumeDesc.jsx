
import React from 'react';
import PropTypes from 'prop-types';

/**
 * A React component that displays a volume's description.
 *
 * @param {Object} props - The component's props.
 * @param {string} props.desc - The description to be displayed.
 * @return {JSX.Element} - A React JSX element.
 */
export default function VolumeDesc(props) {
    return (
        <>
            <h2>Description</h2>
            <div dangerouslySetInnerHTML={{__html: props.desc}}></div>
        </>
    );
}

VolumeDesc.propTypes = {
    desc: PropTypes.string.isRequired,
};
