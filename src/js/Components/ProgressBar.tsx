
import React from 'react';
import PropTypes from 'prop-types';

interface ProgressBarProps {
    heading?: string;
    color?: string;
    filledColor?: string;
    emptyColor?: string;
    value: number;
    max: number;
}

const ProgressBar: React.FC<ProgressBarProps> = ({heading, value, max, color='white', filledColor='green', emptyColor='black'} :ProgressBarProps) => {
    const outerStyle = {
        'border-radius': '12px',
        'background-color': emptyColor,
        'margin': '8px',
    };
    const innerStyle = {
        'display': 'flex',
        'justify-content': 'space-between',
        'background-color': filledColor,
        'width': `${value/max*100}%`,
        'padding': '4px 8px',
        'text-align': 'right',
    };
    const textStyle = {
        'margin': '0',
        'color': color,
    };

    return (
        <>
            <div style={outerStyle} className='progress-bar'>
                <div style={innerStyle}>
                    <h5 style={textStyle}>{heading}</h5>
                    <h5 style={textStyle}>{`${value}/${max}`}</h5>
                </div>
            </div>
        </>
    );
};
export default ProgressBar;

ProgressBar.propTypes = {
    heading: PropTypes.string,
    color: PropTypes.string,
    filledColor: PropTypes.string,
    emptyColor: PropTypes.string,
    value: PropTypes.number.isRequired,
    max: PropTypes.number.isRequired,
};
