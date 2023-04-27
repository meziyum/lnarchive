
export const reactSelectStyle = {
    control: (provided, state) => ({
        ...provided,
        backgroundColor: 'white',
        '&:hover': {borderColor: 'lightgrey'},
        border: '1px solid lightgrey',
        boxShadow: state.isFocused ? '0 0 0 1px #387ef2' : null,
    }),
    option: (provided) => ({
        ...provided,
        '&:hover': {backgroundColor: '#387ef2', color: 'white'},
    }),
    multiValue: (provided) => ({
        ...provided,
        backgroundColor: '#387ef2',
        borderRadius: '20px',
        padding: '2px 6px',
    }),
    multiValueLabel: (provided) => ({
        ...provided,
        color: 'white',
    }),
    multiValueRemove: (provided) => ({
        ...provided,
        color: 'white',
        '&:hover': {color: 'red'},
    }),
};
