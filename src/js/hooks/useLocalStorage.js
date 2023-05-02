import {useState, useEffect} from 'react';

/**
Custom React hook for managing state in localStorage
@param {string} key - The key under which to store the value in localStorage
@param {any} initialValue - The initial value to use if no value is found in localStorage
@param {function} [callback] - Optional callback function to be called when the value is updated
@return {[any, function, function]} An array containing the current value, a function to update the value, and a function to delete the value from localStorage
*/
export default function useLocalStorage(key, initialValue, callback) {
    const [value, setValue] = useState(() => {
        const storedValue = window.localStorage.getItem(key);
        return storedValue !== null ? JSON.parse(storedValue) : initialValue;
    });

    useEffect(() => {
        window.localStorage.setItem(key, JSON.stringify(value));
        if (callback) {
            callback(value);
        }
    }, [key, value, callback]);

    const updateValue = (newValue) => {
        setValue(newValue);
    };

    const deleteValue = () => {
        window.localStorage.removeItem(key);
        setValue(initialValue);
    };

    return [value, updateValue, deleteValue];
}
