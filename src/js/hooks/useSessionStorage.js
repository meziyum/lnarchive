
import {useState, useEffect} from 'react';

/**
A custom React hook to manage values in the session storage.
@param {string} key - The key to be used for the session storage.
@param {*} initialValue - The initial value to be set if the key does not exist in the session storage.
@param {function} callback - An optional callback function to be executed whenever the value changes.
@return {Array} An array with three elements: the current value, a function to update the value, and a function to delete the value from the session storage.
*/
export default function useSessionStorage(key, initialValue, callback) {
    const [value, setValue] = useState(() => {
        const storedValue = window.sessionStorage.getItem(key);
        return storedValue !== null ? JSON.parse(storedValue) : initialValue;
    });

    useEffect(() => {
        window.sessionStorage.setItem(key, JSON.stringify(value));
        if (callback) {
            callback(value);
        }
    }, [key, value, callback]);

    const updateValue = (newValue) => {
        setValue(newValue);
    };

    const deleteValue = () => {
        window.sessionStorage.removeItem(key);
        setValue(initialValue);
    };

    return [value, updateValue, deleteValue];
}
