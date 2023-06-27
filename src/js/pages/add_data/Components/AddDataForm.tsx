
import React from 'react';
import Select from 'react-select';
import TaxSelect from '../../../Components/TaxSelect.jsx';
import {TaxFilter} from '../../../types/TaxFilter';
import {reactSelectStyle} from '../../../style/reactSelectStyles';

/* eslint-disable no-undef */
const userNonce = lnarchiveVariables.nonce;
const isLoggedIn = Boolean(lnarchiveVariables.isLoggedIn);
const wpRequestURL = lnarchiveVariables.wp_rest_url;
/* eslint-enable no-undef */
/* eslint-enable no-undef */

interface AddFormDataProps {
    novelFilters: TaxFilter[];
    volumeFilters: TaxFilter[];
}

interface FormStates {
    selectedImage: string;
    type: {
        label: string;
        value: string;
    };
    filters: TaxFilter[];
    title: string;
    cover: string;
    alt: string;
    desc: string;
}

const AddFormData: React.FC<AddFormDataProps> = ({novelFilters, volumeFilters}: AddFormDataProps) => {
    const [formStates, updateFormStates] = React.useState<FormStates>({
        selectedImage: '',
        type: {label: 'Novel', value: 'novel'},
        filters: novelFilters,
        title: '',
        cover: '',
        alt: '',
        desc: '',
    });
    const [selectedTax, updateSelectedTaxs] =React.useState({});

    const handleType = (data: FormStates['type']) => {
        updateFormStates( (prev) => ({
            ...prev,
            type: data,
            filters: data.value=='novel' ? novelFilters : volumeFilters,
        }));
    };

    const handleFilter = (data, taxQueryName) => {
        updateSelectedTaxs( (prev) => ({
            ...prev,
            [taxQueryName]: data,
        }));
        console.log(selectedTax);
    };

    const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const coverRes = await fetch(`${wpRequestURL}media`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'image/webp',
                'Content-Disposition': `attachment; filename="${formStates.selectedImage}.webp"`,
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                status: 'draft',
                title: formStates.title,
                alt_text: `${formStates.title} Cover`,
                description: `${formStates.title} Cover`,
            }),
        });
        const coverData = await coverRes.json();
        console.log(coverData);

        fetch( `${wpRequestURL}${formStates.type.value}s`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                status: 'draft',
                title: formStates.title,
                tags: selectedTax['tags'],
            }),
        });
    };

    const handleChange = (event) => {
        updateFormStates( (prev) => ({
            ...prev,
            [event.target.name]: event.target.value,
        }));
        console.log(formStates);
    };

    const handleImageChange = (event) =>{
        const selectedImage = event.target.files[0];
        const reader = new FileReader();

        reader.onload = (e) => {
            updateFormStates( (prev) => ({
                ...prev,
                selectedImage: e.target.result,
            }));
        };
        reader.readAsDataURL(selectedImage);
    };

    return (
        <>
            {isLoggedIn ?
                <form onSubmit={handleSubmit}>
                    <label htmlFor="data-type">Select Type of Data:</label>
                    <Select
                        id="data-type"
                        options={[
                            {label: 'Novel', value: 'novel'},
                            {label: 'Volume', value: 'volume'},
                        ]}
                        value={formStates.type}
                        isClearable={false}
                        onChange={handleType}
                        styles={reactSelectStyle}
                    />
                    <label htmlFor="title">Title</label>
                    <input id="title" name="title" type='text' placeholder='Title' value={formStates.title} onChange={handleChange}/>
                    <input id="cover" name="cover" type="file" accept="image/webp" onChange={handleImageChange} value={formStates.cover}/>
                    {formStates.selectedImage && (
                        <>
                            <h3>Selected Cover:</h3>
                            <img src={formStates.selectedImage} alt="Selected Novel Cover" />
                        </>
                    )}
                    {formStates.type.value === 'novel' ?
                        <>
                            <label htmlFor="alt">Alternate Names</label>
                            <textarea id="alt" name="alt" placeholder='Alternate Names(separate with commas)' value={formStates.alt} onChange={handleChange}/>
                        </> :
                        <>
                            <label htmlFor="desc">Description</label>
                            <textarea id="desc" name="desc" placeholder='Description' value={formStates.desc} onChange={handleChange}/>
                        </>
                    }
                    {formStates.filters.map( (tax) =>{
                        return (
                            <TaxSelect key={`${tax.taxQueryName}_filter`} {...tax} handleFilter={handleFilter}/>
                        );
                    })}
                    <button type="submit" className='addData'>Add Data</button>
                </form> :
                <p>You need to be logged in to Add Data</p>
            }
        </>
    );
};

export default AddFormData;
