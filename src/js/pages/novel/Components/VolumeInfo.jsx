
import React from 'react';
import PropTypes from 'prop-types';

/**
Renders volume informatNameion including ISBN, publication date, and narrator for audiobooks.
@param {string} isbn - The ISBN of the volume.
@param {string} publishedDate - The publication date of the volume.
@param {Array.<{id: number, name: string}>} narrator - The array of narrators for an audiobook volume.
@param {string} formatName - The formatName of the volume (e.g. "Hardcover", "Paperback", "Ebook", etc).
@return {JSX.Element} - The table row element displaying volume informatNameion.
*/
export default function VolumeInfo({isbn, publishedDate, translator, narrator, formatName}) {
    return (
        <>
            <tr>
                <th>Translator</th>
                <td>
                    {translator.map( (person) => (
                        person.name
                    ))}
                </td>
            </tr>
            {
                formatName == 'Audiobook' &&
                <tr>
                    <th>Narrator</th>
                    <td>
                        {narrator.map( (person) => (
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
}

VolumeInfo.propTypes = {
    isbn: PropTypes.string,
    publishedDate: PropTypes.string,
    translator: PropTypes.array,
    narrator: PropTypes.array,
    formatName: PropTypes.string.isRequired,
};
