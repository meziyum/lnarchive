
import {formatDate, getCurrentMonthNameByNo} from "./utilities";

test('Format Date Utility function', () => {
    expect(formatDate('2023-07-14')).toBe('14 July, 2023');
});

test('Get the month name by month index helper function', () => {
    expect(getCurrentMonthNameByNo(1)).toBe('February');
});
