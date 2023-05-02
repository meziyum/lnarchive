
import {useState, useCallback} from 'react';

/**
 * A custom hook for toggling a boolean state value.
 *
 * @param {boolean} initialValue - The initial value of the boolean state.
 * @return {Array} An array containing the current boolean state and a toggle function.
 */
export default function useToggle(initialValue = false) {
    const [value, setValue] = useState(initialValue);

    const toggleValue = useCallback(() => {
        setValue((prevValue) => !prevValue);
    }, []);

    return [value, toggleValue];
}

