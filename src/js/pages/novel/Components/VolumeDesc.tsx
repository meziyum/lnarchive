
import React from 'react';
import PropTypes from 'prop-types';

interface VolumeDescProps {
    desc: string;
}

/**
 * A React component that displays a volume's description.
 *
 * @param {Object} props - The component's props.
 * @param {string} props.desc - The description to be displayed.
 * @return {JSX.Element} - A React JSX element.
 */
const VolumeDesc: React.FC<VolumeDescProps> = ({desc}: VolumeDescProps) => {
    return (
        <>
            <h2>Description</h2>
            <div dangerouslySetInnerHTML={{__html: desc}}></div>
        </>
    );
};
export default VolumeDesc;

VolumeDesc.propTypes = {
    desc: PropTypes.string.isRequired,
};
