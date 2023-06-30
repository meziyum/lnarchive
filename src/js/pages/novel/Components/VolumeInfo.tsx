
import React from 'react';
import PropTypes from 'prop-types';
import PersonType from '../../../types/PersonType';

interface VolumeInfoProps {
    isbn?: string;
    publishedDate?: string;
    translator?: Array<PersonType>;
    narrator?: Array<PersonType>;
    formatName: string;
}

/**
Renders volume informatNameion including ISBN, publication date, and narrator for audiobooks.
@param {string} isbn - The ISBN of the volume.
@param {string} publishedDate - The publication date of the volume.
@param {Array.<{id: number, name: string}>} narrator - The array of narrators for an audiobook volume.
@param {Array.<{id: number, name: string}>} translator - The array of translators for the volume.
@param {string} formatName - The formatName of the volume (e.g. "Hardcover", "Paperback", "Ebook", etc).
@return {JSX.Element} - The table row element displaying volume informatNameion.
*/
const VolumeInfo: React.FC<VolumeInfoProps> = ({isbn, publishedDate, translator, narrator, formatName}: VolumeInfoProps) => {
    return (
        <>
            <tr>
                <th>Translator</th>
                <td>
                    {translator && translator.map( (person) => (
                        person.name
                    ))}
                </td>
            </tr>
            {
                formatName == 'Audiobook' &&
                <tr>
                    <th>Narrator</th>
                    <td>
                        {narrator && narrator.map( (person) => (
                            <p key={person.id}>{person.name}</p>
                        ))}
                    </td>
                </tr>
            }
            { isbn &&
                <tr>
                    <th>ISBN</th>
                    <td>{isbn}</td>
                </tr>
            }
            { publishedDate &&
                <tr>
                    <th>Publication Date</th>
                    <td>{publishedDate}</td>
                </tr>
            }
        </>
    );
};
export default VolumeInfo;

VolumeInfo.propTypes = {
    isbn: PropTypes.string,
    publishedDate: PropTypes.string,
    translator: PropTypes.array,
    narrator: PropTypes.array,
    formatName: PropTypes.string.isRequired,
};
