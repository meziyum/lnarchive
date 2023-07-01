
import React from 'react';
import PropTypes from 'prop-types';
import {formatDate} from '../../../helpers/utilities';
import TermType from '../../../types/TermType';

const urlParams = new URLSearchParams(window.location.search);

interface FormatsListProps {
    formats: Array<{
        id: number;
        name: string;
    }>;
    meta: object;
    narrator: Array<TermType>;
    translator: Array<TermType>;
    formatFilter: string;
    handleClick(volumeISBN: string, volumeDate: string, translator: Array<TermType>, narrator: Array<TermType>, defaultFormatName: string) : void;
}

/**
 * Displays a list of available formats and handles the click event when a format is selected.
 * @param {Object[]} formats - An array of objects representing the available formats.
 * @param {Object} meta - An object containing metadata for the volume.
 * @param {TermType} narrator - The name of the narrator for the audiobook format.
 * @param {TermType} translator - The name of the translator of the novel.
 * @param {string} formatFilter - The current format filter applied to the novel.
 * @param {Function} handleClick - A function that handles the click event when a format is selected.
 * @return {JSX.Element} - Returns the list of available formats as JSX.
 */
const FormatsList: React.FC<FormatsListProps> = ({formats, meta, translator, narrator, handleClick, formatFilter}: FormatsListProps) =>{
    const [selectedFormat, setSelectedFormat] = React.useState(formatFilter);

    React.useEffect( () => {
        setSelectedFormat(formatFilter);
    }, [formatFilter]);

    const handleFormatClick = (formatName: string) => {
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
};
export default FormatsList;

FormatsList.propTypes = {
    formats: PropTypes.array.isRequired,
    meta: PropTypes.object.isRequired,
    translator: PropTypes.array.isRequired,
    narrator: PropTypes.array.isRequired,
    handleClick: PropTypes.func.isRequired,
    formatFilter: PropTypes.string.isRequired,
};
