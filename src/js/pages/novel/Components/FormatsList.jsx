
import React from 'react';
import PropTypes from 'prop-types';
import {formatDate} from '../../../helpers/utilities.ts';

const urlParams = new URLSearchParams(window.location.search);

/**
 * Displays a list of available formats and handles the click event when a format is selected.
 * @param {Object[]} formats - An array of objects representing the available formats.
 * @param {Object} meta - An object containing metadata for the volume.
 * @param {string} narrator - The name of the narrator for the audiobook format.
 * @param {string} formatFilter - The current format filter applied to the novel.
 * @param {Function} handleClick - A function that handles the click event when a format is selected.
 * @param {string} defaultFormatName - The name of the default format to be selected on initial load.
 * @return {JSX.Element} - Returns the list of available formats as JSX.
 */
export default function FormatsList({formats, meta, translator, narrator, handleClick, formatFilter}) {
    const [selectedFormat, setSelectedFormat] = React.useState(formatFilter);

    React.useEffect( () => {
        setSelectedFormat(formatFilter);
    }, [formatFilter]);

    const handleFormatClick = (formatName) => {
        if (formatName == selectedFormat) {
            return;
        }

        setSelectedFormat(formatName);
        handleClick(
            meta[`isbn_${formatName}_value`][0],
            formatDate(meta[`published_date_value_${formatName}`][0]),
            translator,
            narrator,
            formatName,
        );
        urlParams.set('formatFilter', formatName);
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.history.pushState(null, '', newUrl);
    };

    return (
        <>
            {formats[0].name !== 'None' &&
                formats.map((format) => (
                    <a
                        key={format.id} className={`anchor-button format-button ${selectedFormat === format.name ? 'selected-format' : ''}`} onClick={() => handleFormatClick(format.name)}>
                        {format.name}
                    </a>
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
    formatFilter: PropTypes.string.isRequired,
};
