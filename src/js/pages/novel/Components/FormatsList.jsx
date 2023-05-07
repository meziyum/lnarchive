
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Displays a list of available formats and handles the click event when a format is selected.
 * @param {Object[]} formats - An array of objects representing the available formats.
 * @param {Object} meta - An object containing metadata for the volume.
 * @param {string} narrator - The name of the narrator for the audiobook format.
 * @param {Function} handleClick - A function that handles the click event when a format is selected.
 * @param {string} defaultFormatName - The name of the default format to be selected on initial load.
 * @return {JSX.Element} - Returns the list of available formats as JSX.
 */
export default function FormatsList({formats, meta, translator, narrator, handleClick}) {
    const [selectedFormat, setSelectedFormat] = React.useState(formats[0].name);

    React.useEffect( () => {
        setSelectedFormat(formats[0].name);
    }, [formats[0].name]);

    const handleFormatClick = (formatName) => {
        setSelectedFormat(formatName);
        handleClick(
            meta[`isbn_${formatName}_value`][0],
            meta[`published_date_value_${formatName}`][0],
            translator,
            narrator,
            formatName,
        );
    };

    return (
        <>
            {
                formats.map((format) => (
                    <button
                        key={format.id} className={`format-button ${selectedFormat === format.name ? 'selected-format' : ''}`} onClick={() => handleFormatClick(format.name)}>
                        {format.name}
                    </button>
                ))
            }
        </>
    );
}

FormatsList.propTypes = {
    formats: PropTypes.array.isRequired,
    meta: PropTypes.object.isRequired,
    translator: PropTypes.array.isRequired,
    narrator: PropTypes.array.isRequired,
    handleClick: PropTypes.func.isRequired,
};
